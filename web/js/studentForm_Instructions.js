/*
  This is a set of js functions that are called in regards 
  to the Student View - Nomination Form

       --Contents--
  Instruction Strings:
    These are the strings that are used to create the instructions 
	shown in the Student Form. These are clearly labelled as to which 
	section they apply to.

*/


/*	---------------------- Instruction Strings ----------------------  */

/*
  This takes a set of Sstrings and attatches them as instructions to the relevant section of the student form
  
  These Strings use variable names that match the suffix of the tag#id's they relate to,
      ie. 'name' refers to '#instructions_name' tag in the student form
	  
*/

//declare the list of instruction Strings as an array
var list = { 
	//set the actual text used here
	'name' 			: "This name is used for the 'team preferrances' section at the bottom. Preferrably this should also be the name on your student card.",
	'pass_fail'		: "..." ,
	'degrees' 		: ".."  ,
	'majors' 		: "..."  ,
	'skills' 		: ".."  ,
	'gpa' 			: "gpa stuff"  ,
	'projPref' 		: "project preferance"  ,
	'projectJust'	: "..."  ,
	'desiredStud' 	: ".."  ,
	'undesiredStud'	: "..."  ,
};



//call to the function that goes through the list
//attachInstructions();



/*	
	There is a placeholder which the user hovers over to display the instructions
	The actual instructions are contained within the title attribute of the <a> tag
	
	So we have to find the relevant tag then attach the desired code
*/

function attachInstructions()
{
	//draw trace window
	document.write('<div style="clear:both;display:block;height:auto;background-color:#eee;">')
	document.write('test = ' + document.getElementById('header') + '<br>');
	var suffix; 	// this retains the suffix of the desired tag
	var target;		// this is the target DOM element
	var targetStr;	// this is the string #id of the DOM element
	
	for(obj in list)
	{
		suffix = obj;
		targetStr = "instructions_"+suffix;
		target = document.getElementById(targetStr);
		
		document.write(suffix + ' = ' + list[obj] + ' <br> DOM: targetStr = ' + targetStr + ', target = ' + target + '<br>');
		document.write();
	}
	document.write('</div>');
}



