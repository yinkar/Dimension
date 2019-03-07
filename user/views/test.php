<!DOCTYPE html>
<html>
<head>
	<title>test</title>

	+[dimension]
		Foton::use();
	-[dimension]

	+[foton]
		#container {
			background-color: #cccccc;
		}
	-[foton]
</head>
<body>
	<div id="container" class="inline shadow padding margin">
		Test view.
		<div id="rastgele" class="button button-red button-hover-blue">
		</div>
	</div>
	
	+[dimension]
		Hadron::use();
	-[dimension]

	+[hadron]
		h('#rastgele').put(rand(1, 20));
	-[hadron]
</body>
</html>