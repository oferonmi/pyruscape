//TEST BEACONS
//alert('testing first sequence');

function showUpload(type){
		document.getElementById('loadBtn').disabled = "disabled";
		var foo = document.getElementById('fooBar');
		var dCount =(document.getElementById('count').value)-0;
								
		for(var i = 0; i < dCount; i = i+1){
			
			var lordElmt = document.createElement("div");		
			lordElmt.setAttribute("class", "fileupload fileupload-new");
			lordElmt.setAttribute("data-provides", "fileupload");
			
			var kidElmtA = document.createElement("div");
			kidElmtA.setAttribute("class","input-apend");
			
			// first inner element assembly
			var kidElmtAA = document.createElement("div");
			kidElmtAA.setAttribute("class","uneditable-input span2");
			
			var kidElmtAAA = document.createElement("i");
			kidElmtAAA.setAttribute("class","icon-file fileupload-exists");
			
			var kidElmtAAB = document.createElement("span");
			kidElmtAAB.setAttribute("class","fileupload-preview");
			
			//load elements into first assembly
			kidElmtAA.appendChild(kidElmtAAA);
			kidElmtAA.appendChild(kidElmtAAB);
			
			// second inner element assembly
			var kidElmtAB = document.createElement("span");
			kidElmtAB.setAttribute("class","btn btn-file");
			
			var kidElmtABA = document.createElement("span");
			kidElmtABA.setAttribute("class","fileupload-new");
			kidElmtABA.innerHTML = "Select File";
			
			var kidElmtABB = document.createElement("span");
			kidElmtABB.setAttribute("class","fileupload-exists");
			kidElmtABB.innerHTML = "Change";
			
			var kidElmtABC = document.createElement("input");
			kidElmtABC.setAttribute("type",type);
			kidElmtABC.setAttribute("accept","application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,\
			image/jpeg, image/gif, image/png, application/pdf, application/vnd.ms-powerpoint, application/msword,\
			application/x-msaccess");
			kidElmtABC.setAttribute("name","file"+i);
			kidElmtABC.setAttribute("id","file"+i);
			
			//load elements into second assembly
			kidElmtAB.appendChild(kidElmtABA);
			kidElmtAB.appendChild(kidElmtABB);
			kidElmtAB.appendChild(kidElmtABC);
			
			//third inner element assembly
			var kidElmtAC = document.createElement("a");
			kidElmtAC.setAttribute("href","#");
			kidElmtAC.setAttribute("class", "btn fileupload-exists");
			kidElmtAC.setAttribute("data-dismiss", "fileupload");
			kidElmtAC.innerHTML = "Remove";
			
			//doing final compact assembly
			kidElmtA.appendChild(kidElmtAA);
			kidElmtA.appendChild(kidElmtAB);
			kidElmtA.appendChild(kidElmtAC);
			
			lordElmt.appendChild(kidElmtA);
			
			foo.appendChild(lordElmt);
					
		
		//TEST BEACONS
		//var fileUp = document.createElement("input");
		//fileUp.setAttribute("type",type);
		//foo.appendChild(fileUp);
		}
	}
	
	//TEST BEACONS
	//alert('testing second sequence');