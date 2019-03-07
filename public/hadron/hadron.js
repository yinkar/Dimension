h = function(elementName) {
	this.elementRaw;
	this.element;
	
	this.element = document.querySelectorAll(elementName);

	
/*
	if(elementName.charAt(0) == '#') {
		elementName = elementName.substring(1);
		this.element = document.getElementById(elementName);
	}
	else if(elementName.charAt(0) == '.') {
		elementName = elementName.substring(1);
		this.element = document.getElementsByClassName(elementName);
	}
	else {
		this.element = document.getElementsByTagName(elementName)[0];
	}
*/
	
	this.put = function(data, select = null) {
		if(this.element.length > 1) {
			if(select != null && isNumeric(select)) {
				this.element[select].innerHTML = data;
			}
			else {
				for(i = 0; i < this.element.length; i++) {
					this.element[i].innerHTML = data;
				}
			}
		}
		else {			
			this.element[0].innerHTML = data;
		}
		return this;
	}

	this.putClean = function(data, select = null) {

		data = data.replace(/(<([^>]+)>)/ig, "");

		if(this.element.length > 1) {
			if(select != null && isNumeric(select)) {
				this.element[select].innerHTML = data;
			}
			else {
				for(i = 0; i < this.element.length; i++) {
					this.element[i].innerHTML = data;
				}
			}
		}
		else {			
			this.element[0].innerHTML = data;
		}
		return this;
	}

	this.content = function(select = null) {
		if(this.element.length > 1) {
			if(select != null && isNumeric(select)) {
				return this.element[select].innerHTML;
			}
			else {
				for(i = 0; i < this.element.length; i++) {
					return this.element[i].innerHTML;
				}
			}
		}
		else {			
			return this.element[0].innerHTML;
		}
		return this;
	}

	this.getVal = function() {
		if(this.element.length > 1) {
			if(select != null && isNumeric(select)) {
				return this.element[select].value;
			}
			else {
				for(i = 0; i < this.element.length; i++) {
					return this.element[i].value;
				}
			}
		}
		else {			
			return this.element[0].value;
		}
		return this;
	}

	this.setVal = function(value) {
		if(this.element.length > 1) {
			if(select != null && isNumeric(select)) {
				this.element[select].value = value;
			}
			else {
				for(i = 0; i < this.element.length; i++) {
					this.element[i].value = value;
				}
			}
		}
		else {			
			this.element[0].value = value;
		}
		return this;
	}
	
	this.invisible = function(select = null) {
		if(this.element.length > 1) {
			if(select != null && isNumeric(select)) {
				this.element[select].style.display = "none";
			}
			else {
				for(i = 0; i < this.element.length; i++) {
					this.element[i].style.display = "none";
				}
			}
		}
		else {			
			this.element[0].style.display = "none";
		}
		return this;
	}
	
	this.visible = function(select = null) {
		if(this.element.length > 1) {
			if(select != null && isNumeric(select)) {
				this.element[select].style.display = "block";
			}
			else {
				for(i = 0; i < this.element.length; i++) {
					this.element[i].style.display = "block";
				}
			}
		}
		else {			
			this.element[0].style.display = "block";
		}
		return this;
	}

	this.on = function(event, callback, select = null) {
		if(this.element.length > 1) {
			if(select != null && isNumeric(select)) {
				this.element[select]['on' + event] = callback;
			}
			else {
				for(i = 0; i < this.element.length; i++) {
					this.element[i]['on' + event] = callback;
				}
			}
		}
		else {			
			this.element[0]['on' + event] = callback;
		}
		return this;
	}

	this.menutize = function(items, direction = 'vertical', theme = 'silver', itemclass = '') {

		this.element[0].classList.add('menu');
		this.element[0].classList.add(direction);


		if(theme !== 'silver') {
			this.element[0].classList.add('menu-' + theme);
		}

		itemclass = ' class="' + itemclass + '"';


		var str = '';

		str += '<ul>';

		for(i = 0; i < items.length; i++) {
			str += '<li><a' + itemclass + ' href="' + items[i][1] + '">' + items[i][0] + '</a></li>';
		}

		str += '</ul>';

		this.element[0].innerHTML = str;
	}

	return this;

}

rand = function(begin, end) {
	return Math.floor(Math.random() * end) + begin;
}

range = function(first, second, third) {
	let numbers = [];

	if(third == null) {
		if(second == null) {
			for(i = 1; i <= parseInt(first); i++) {
				numbers.push(i);
			}
		}
		else {
			for(i = parseInt(first); i <= parseInt(second); i++) {
				numbers.push(i);
			}
		}
	}
	else {
		for(i = parseInt(first); i <= parseInt(second); i += parseInt(third)) {
			numbers.push(i);
		}
	}
	
	return numbers;
}

url = function() {
	this.query = window.location.hash.substr(1).split('&');

	this.get = {};

	this.item = [];

	for(i = 0; i < query.length; i++) {
		if(!query[i].includes('=')) {
			this.get[query[i]] = 'true';
			continue;
		}
		this.item = this.query[i].split('=');
		this.get[this.item[0]] = this.item[1];
	}

	return this.get;
}

isNumeric = function(data) {
	return !isNaN(parseFloat(data)) && isFinite(data);
}