// Adapted from https://www.w3schools.com/howto/howto_js_sort_table.asp
// Only does string comparisons not numbers, dates, etc.

function sortTableInit() {
	// Hide arrows on all columns except the first one:
	for (var j = 0, colsnum = 4; j < colsnum; j++)
	{
		if (j == 0)
		{
			document.getElementById("arrw_u" + j).style.display = "inline";
			document.getElementById("arrw_d" + j).style.display = "none";
			document.getElementById("arrw_n" + j).style.display = "none";
		}
		else
		{
			document.getElementById("arrw_u" + j).style.display = "none";
			document.getElementById("arrw_d" + j).style.display = "none";
			document.getElementById("arrw_n" + j).style.display = "inline";
		}
	}
}

function sortTable(n) {
	var switchcount = 0;
	var x, y = "";
	var table = document.getElementById("tableToSort");
	var switching = true;
	// Set the sorting direction to ascending:
	var dir = "asc"; 
	/* Make a loop that will continue until
	no switching has been done: */
	while (switching) {
		var i = 0;
		// Start by saying: no switching is done:
		switching = false;
		var rows = table.getElementsByTagName("TR");
		// Start by saying there should be no switching:
		var shouldSwitch = false;
		/* Loop through all table rows (except the
		first, which contains table headers): */
		for (i = 1, rowlen = rows.length - 1; i < rowlen; i++) {
			/* Get the two elements you want to compare,
			one from current row and one from the next: */
			x =     rows[i].getElementsByTagName("TD")[n].textContent.toLowerCase();
			y = rows[i + 1].getElementsByTagName("TD")[n].textContent.toLowerCase();
			/* Check if the two rows should switch place,
			based on the direction, asc or desc: */
			if (dir == "asc") {
				if (x > y) {
					// If so, mark as a switch and break the loop:
					shouldSwitch = true;
					break;
				}
			} else if (dir == "desc") {
				if (x < y) {
					// If so, mark as a switch and break the loop:
					shouldSwitch = true;
					break;
				}
			}
		}
		if (shouldSwitch) {
			/* If a switch has been marked, make the switch
			and mark that a switch has been done: */
			rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
			switching = true;
			// Each time a switch is done, increase this count by 1:
			switchcount ++; 
		} else {
			/* If no switching has been done AND the direction is "asc",
			set the direction to "desc" and run the while loop again. */
			if ((switchcount == 0) && (dir == "asc")) {
				dir = "desc";
				switching = true;
			}
		}
	}
	// Hide arrows on all columns except the one clicked:
	for (var j = 0, colsnum = 4; j < colsnum; j++)
	{
		if (j != n)
		{
			document.getElementById("arrw_u" + j).style.display = "none";
			document.getElementById("arrw_d" + j).style.display = "none";
			document.getElementById("arrw_n" + j).style.display = "inline";
		}
		else if (dir == "asc")
		{
			document.getElementById("arrw_u" + j).style.display = "inline";
			document.getElementById("arrw_d" + j).style.display = "none";
			document.getElementById("arrw_n" + j).style.display = "none";
		}
		else if (dir == "desc")
		{
			document.getElementById("arrw_u" + j).style.display = "none";
			document.getElementById("arrw_d" + j).style.display = "inline";
			document.getElementById("arrw_n" + j).style.display = "none";
		}
	}
}
