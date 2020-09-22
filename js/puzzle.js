	function changeInput() {
		
		var answers = $("[name='single']");
		
		$("[name='single']").toggleClass("hide");
		$("[name='multi']").toggleClass("hide");
		/*
		for (var x = 0; x < answers.length; x++) {
			answers[x].innerHTML = "<p>dfgd</p>";
		}
		*/
	}
	
	function eraseText() {
        document.getElementById("comment").value = "";
    }
	
	function showSolution() {
		xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				//alert(this.responseText);
				if (this.responseText == 'true') {
					if($("[name='single']").hasClass("hide")) {
						var chars = $("[data-char]");		
						for (var i = 0; i < chars.length; i++) {
							chars[i].value = chars[i].getAttribute('data-char');
						}
					} else {
						
						var chars = $(".answer");
						var answers = $("[data-answer]");
						for (var i = 0; i < chars.length; i++) {				
							chars[i].value = answers[i].getAttribute('data-answer');
						}
						
					}	
				} else {
					alert('Access Denied! Please contact System Administrator');
				}
			}
		};
		xhttp.open("GET", "isAdmin.php", false);
		xhttp.send();
		
			
	}
	
	function submitSolution() {
		var answers = $("[data-answer]");		
		var guess = "";
		var success = true;
		
		if($("[name='single']").hasClass("hide")) {
			for (var x = 0; x < answers.length; x++) {
				
				var inputs = answers[x].getElementsByClassName("multi");
				
				for (var i = 0; i < inputs.length; i++) {
					guess += inputs[i].value;				
				}
				
				if (guess == answers[x].getAttribute('data-answer')){
					markSucced(inputs);
				}
				else {
					markFailed(inputs);
					success = false;
				}
				
				guess = "";
			}
		} else {
			for (var x = 0; x < answers.length; x++) {
				
				guess = answers[x].getElementsByClassName("answer")[0].value;								
				if (guess == answers[x].getAttribute('data-answer')){
					markSucced(answers[x].getElementsByClassName("answer"));
				}
				else {
					markFailed(answers[x].getElementsByClassName("answer"));
					success = false;
				}
				
				guess = "";
			}
		}		
		
		if (success) {
			alert("Congratulations! You have solved it!");
		} else {
			alert("Incorrect! Try again!");
		}
		
	}
	
	function addPuzzle () {
		//alert("sfsdf");
		var pairs = $(".pairs");
		var success = true;
		var positions = new Array();
		var synonyms = new Array();
		//alert(pairs.length);
		xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                //alert(this.responseText);
				positions.push(pos);
				synonyms.push(pair[0].value);
            }
			
        };
		for (var x = 0; x < pairs.length; x++) {
			var pair = pairs[x].getElementsByTagName("input");
			var pos = checkSynonym(pairs[x].getElementsByClassName("char")[0].innerHTML, pair[0].value);
			//alert(pair[1].value.length);
			if (pair[1].value.length < 3  || pair[0].value.length < 3) {
				alert("The synonym or clue field is empty! Please, fill in all fields!");
				return;
			}
			
			if (pos > -1) {
				//alert(pair[1].value);
				//window.location = "newWordPair.php?clue=" + pair[0].value + "&synonym=" + pair[1].value;
				
				xhttp.open("GET", "process_wordPairs.php?synonym=" + pair[0].value + "%2C+" + pair[1].value, false);
				xhttp.send();
				
			}
			else {
				alert(pair[0].value + " does not contain character " + pairs[x].getElementsByClassName("char")[0].innerHTML);
				success = false;
				return;
			}
		}
		
		if (success) {
			var name = $("[data-name]");
			var myObj = {
				"name": name[0].getAttribute('data-name'),
				"positions": [],
				"synonyms" : []				
			 }
			 myObj.positions = positions;
			 myObj.synonyms = synonyms;
			//alert(name[0].getAttribute('data-name'));
			var db = JSON.stringify(myObj);
			window.location.assign("addPuzzleDB.php?x=" + db);
		}
	}
	
	function updatePuzzle() {
		
		var pairs = $(".pairs");
		var success = true;
		var positions = new Array();
		var synonyms = new Array();
		//alert(pairs.length);
		xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                //alert(this.responseText);
            }
        };
		for (var x = 0; x < pairs.length; x++) {
			var pair = pairs[x].getElementsByTagName("input");
			var pos = checkSynonym(pairs[x].getElementsByClassName("char")[0].innerHTML, pair[0].value);
			//alert(pair[1].value.length);
			if (pair[1].value.length < 3  || pair[0].value.length < 3) {
				alert(" The synonym or clue field is empty! Please, fill in all fields!");
				return;
			}
			
			if (pos > -1) {
				//alert(pair[1].value);
				//window.location = "newWordPair.php?clue=" + pair[0].value + "&synonym=" + pair[1].value;
				
				xhttp.open("GET", "newWordPair.php?clue=" + pair[1].value + "&synonym=" + pair[0].value, false);
				xhttp.send();
				positions.push(pos);
				synonyms.push(pair[0].value);
			}
			else {
				alert(pair[0].value + " does not contain character " + pairs[x].getElementsByClassName("char")[0].innerHTML);				
				return;
			}
		}
		
		if (success) {
			
			var name = $("[data-name]");
			var myObj = {
				"name": name[0].getAttribute('data-name'),
				"id" : name[0].getAttribute('data-id'),
				"positions": [],
				"synonyms" : []				
			 }
			myObj.positions = positions;
			myObj.synonyms = synonyms;
			
			var db = JSON.stringify(myObj);
			//alert(name[0].getAttribute('data-id'));
			window.location.href = "updatePuzzleDB.php?x=" + db;
			//window.location.replace("addPuzzleDB.php?x=" + db);
		}
	}
	
	function checkSynonym (letter, synonym) {
		var pos = synonym.search(letter);
		//alert(pos);
		return pos;
	}
	
	function markSucced (inputs) {		
		$(inputs).css({"background-color": "green", "color" : "white"});
	}
	
	function markFailed (inputs) {		
		$(inputs).css({"background-color": "red", "color" : "white"});
	}
	
	$(".inputs").keyup(function () {		
    if (this.value.length == this.maxLength) {
      $(this).next('.inputs').focus();
    }
	});