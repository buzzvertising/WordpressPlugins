function DoSubmit( name, recurring){

	var radios = document.getElementsByName( name );

	for (var i = 0, length = radios.length; i < length; i++) {
		if (radios[i].checked) {
			// do whatever you want with the checked radio
			donation = radios[i].value.split("|");
			// only one radio can be logically checked, don't check the rest
			break;
		}
	}
	var a = donation[0];
	var n = donation[1];
	
	if ( recurring == 1) {
		var input = document.createElement("input");
		input.setAttribute("type", "hidden");
		input.setAttribute("name", "srt");
		input.setAttribute("value", "");
		document.donation.appendChild(input);
		
		document.donation.cmd.value = '_donations';
		document.donation.amount.value = a;
		document.donation.a3.value = a;
		document.donation.p3.value = '1';
		document.donation.t3.value = '0';
		document.donation.src.value = '0';
		document.donation.srt.value = '';
		document.donation.sra.value = '1';
		document.donation.item_name.value = n;
	}
	if ( recurring != 1){
		document.donation.amount.value = a;
		document.donation.a3.value = a;
		document.donation.item_name.value = n;
		document.donation.t3.value = recurring;
	}
	document.donation.submit();
};	
