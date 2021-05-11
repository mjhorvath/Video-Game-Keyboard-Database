// https://stackoverflow.com/questions/13405129/javascript-create-and-save-file
// Browser support is flaky.
// Function to download data to a file
function download(data, filename, type) {
    var file = new Blob([data], {type: type});
    if (window.navigator.msSaveOrOpenBlob) // IE10+
        window.navigator.msSaveOrOpenBlob(file, filename);
    else { // Others
        var a = document.createElement("a"),
                url = URL.createObjectURL(file);
        a.href = url;
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        setTimeout(function() {
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);  
        }, 0); 
    }
}

function document_export_submit_ajax()
{
	collect_legend_data();
	collect_command_data();
//	collect_binding_data();
//	console.log(JSON.stringify(binding_table));
	const url = 'lib/export-submit-html.php';
	$.ajax
	({
		type: 'POST',
		url: url,
		data: JSON.stringify(binding_data),
		contentType: 'application/json; charset=utf-8',
		dataType: 'html',
		success: function(msg)
		{
			do_export(msg);
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			console.log(textStatus, errorThrown);
		}
	});
}

function document_export_main_ajax()
{
	const url = 'lib/export-main-html.php';
	$.ajax
	({
		type: 'POST',
		url: url,
		data: JSON.stringify(binding_data),
		contentType: 'application/json; charset=utf-8',
		dataType: 'html',
		success: function(msg)
		{
			do_export(msg);
		},
		error: function(jqXHR, textStatus, errorThrown)
		{
			console.log(textStatus, errorThrown);
		}
	});
}

function document_export_submit_xhr()
{
	collect_legend_data();
	collect_command_data();
//	collect_binding_data();
	const url = 'lib/export-submit-html.php';
	var xhr = new XMLHttpRequest();
	xhr.open('POST', url, true);
	xhr.addEventListener('load', reqListener);
	xhr.setRequestHeader('Content-Type', 'application/json; charset=utf-8');
	xhr.send(JSON.stringify(binding_data));
}

function reqListener()
{
	do_export(this.responseText);
}

function do_export(content)
{
	// save output to file
	download(content, 'keyboard-diagram-' + binding_data.game_seo + '.html', 'text/html; charset=utf-8');
	// print output to new window
//	var myWindow = window.open('', 'myWindow', '');
//	myWindow.document.write(content);
//	myWindow.document.close();
	// print output to browser console
//	console.log(content);
}
