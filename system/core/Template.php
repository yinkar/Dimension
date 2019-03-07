<?php

class Template {
	private $vars = array();

	public function assign($virtual, $real, $count = true) {
		if(is_array($real)) {
			if($count == true) {
				$i = 0;
				foreach($real as $key => $value) {
					$real[$key]['_counter'] = $i;
					$i++;
				}	
			}
		}

		$this->vars[$virtual] = $real;
	}

	public function render($template, $data = array()) {
		global $config;

		$file_extension = pathinfo($template, PATHINFO_EXTENSION);
		
		$extensions = array('php', 'phtml', 'html');

		if(in_array($file_extension, $extensions)) {
			$path = $config['user_dir'].'/views/'.$template;
		}
		elseif(empty($file_extension)) {
			$path = $config['user_dir'].'/views/'.$template.'.php';
		}
		else {
			echo 'Template filename error';
			die();
		}

		if(file_exists($path)) {
			$content = file_get_contents($path);
			$this->run($this->template_replace($content), $data);
		}
	}

	private function template_replace($content) {
		$content = $this->var_replace($content, $this->vars);

		$content = preg_replace('/\+\[php[\s]*\]/', '<?php ', $content);
		$content = preg_replace('/\[endphp[\s]*\]/', ' ?>', $content);

		$content = preg_replace('/\+\[dimension[\s]*\]/', '<?php ', $content);
		$content = preg_replace('/\-\[dimension[\s]*\]/', ' ?>', $content);
		$content = preg_replace('/\+\[hadron[\s]*\]/', '<script type="text/javascript">', $content);
		$content = preg_replace('/\-\[hadron[\s]*\]/', ' </script>', $content);
		$content = preg_replace('/\+\[foton[\s]*\]/', '<style type="text/css">', $content);
		$content = preg_replace('/\-\[foton[\s]*\]/', '</style>', $content);

		$content = preg_replace('/\!\[each:(\w+)\]/', '<?php foreach($this->vars[\'$1\'] as $value): echo $this->var_replace(\'', $content);
		$content = preg_replace('/\[endeach\]/', '\', $value); endforeach; ?>', $content);

		$content = preg_replace('/\!\[if:([^\r\n}]+)\]/', '<?php if($1): echo \'', $content);
		$content = preg_replace('/\[else\]/', '\'; else: echo \'', $content);
		$content = preg_replace('/\[endif\]/', '\'; endif; ?>', $content);

		/* Markup yapılı */
		$content = preg_replace('/\<php[\s]*\>/', '<?php ', $content);
		$content = preg_replace('/\<\/php[\s]*\>/', ' ?>', $content);

		$content = preg_replace('/\<each data\=\"(\w+)\"\>/', '<?php foreach($this->vars[\'$1\'] as $value): echo $this->var_replace(\'', $content);
		$content = preg_replace('/\<\/each[\s\/]*\>/', '\', $value); endforeach; ?>', $content);

		$content = preg_replace('/\<if cond\=\"([^\r\n}]+)"[\s]*\>/', '<?php if($1): echo \'', $content);
		$content = preg_replace('/\<else[\s\/]*\>/', '\'; else: echo \'', $content);
		$content = preg_replace('/\<\/if[\s]*\>/', '\'; endif; ?>', $content);

		return $content;
	}

	private function var_replace($content, $vars) {
		foreach ($vars as $virtual => $real) {
			if(!is_array($real) && !is_object($real)) {
				$content = preg_replace('/\[\['.$virtual.'\]\]/', $real, $content);

				/* Markup Yapılı */
				$content = preg_replace('/\<item name\=\"'.$virtual.'\"[\s\/]*\>/', $real, $content);
			}
		}

		return $content;
	}

	private function run($code, $data) {
		$tmpfilename = @tempnam('/tmp', 'template');
		$tmpfile = fopen($tmpfilename, 'w+');
		fwrite($tmpfile, $code);
		fclose($tmpfile);
		unset($code);
		unset($tmpfile);

		require $tmpfilename;
		unlink($tmpfilename);
		unset($tmpfilename);
	}
}