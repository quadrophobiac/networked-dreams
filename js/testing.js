//d3.json("Includes/debug.json", function(error, data) {
//	data.forEach(function(d) {
//		d.date = parseDate(d.date);
//		d.close = +d.close;
//	});
        
  d3.json = function(url, callback) {
    return d3.xhr(url, "application/json", callback).response(d3_json);
  };
  // d3.xhr = function(url, mimeType, callback)
  function d3_json(request) {
    return JSON.parse(request.responseText);
  }
  