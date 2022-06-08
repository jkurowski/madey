var Qurl;(function(){var c;Qurl=function(){if(!(this instanceof Qurl)){return new Qurl()}};c=Qurl.prototype;c.query=function(f,g){if(!f){return e()}if(f&&typeof(g)==="undefined"){return e()[f]}if(f&&typeof(g)!=="undefined"){return b(f,g)}};function e(){var h=window.location.search,f=[],g={};if(!h){return g}h=h.replace("?","");f=h.split("&");f.forEach(function(i){var k=decodeURIComponent(i).split("="),l=k[0],j=k[1];g[l]=j});return g}function b(f,g){var i=e(),h=window.location.search;if(g===false){delete i[f]}else{i[f]=g}a(d(i));return i}function a(f){if(history.pushState){history.pushState({},document.title,f)}}function d(g){var f=[];Object.keys(g).forEach(function(h){if(typeof g[h]==="undefined"){f.push(h)}else{f.push(encodeURIComponent(h)+"="+encodeURIComponent(g[h]||""))}});if(f.length===0){return"?"}else{return"?"+f.join("&")}}Qurl.create=Qurl}());

$(document).ready(function(){
    var d = 0;
    var b = 100;
    $("#areaControl").on("change", function() {
        var f = this.options[this.selectedIndex].value;
        var e = Qurl.create();
        e.query("s_area", f);
		window.location.hash = "#szukaj-pietro";
        location.reload()
    });
    $("#roomControl").on("change", function() {
        var f = this.options[this.selectedIndex].value;
        var e = Qurl.create();
        e.query("s_room", f);
		window.location.hash = "#szukaj-pietro";
        location.reload()
    });
    $("#statusControl").on("change", function() {
        var f = this.options[this.selectedIndex].value;
        var e = Qurl.create();
        e.query("status", f);
		window.location.hash = "#szukaj-pietro";
        location.reload()
    });

	$("#myimagemap").mapster({
            singleSelect: true,
            fill : true,
            fillOpacity : 0.6,
            fillColor: '2297c2',
            onMouseover: function(e) {
                $(this).mapster('set',false).mapster('set',true);
            },
            onMouseout: function(e) { 
                 $(this).mapster('set',false);
            },
			clickNavigate:true
        });

	// function a(h) {
	    // var i = $("img#myimagemap"),
	        // f = i.width(),
	        // g = 0,
	        // e = 0;
	    // g = h;
	    // i.mapster("resize", g, d)
	// }

	// function c() {
	    // var f = $("#plan").width(),
	        // g = $("#plan").height(),
	        // e = false;
	    // if (e) {
	        // return
	    // }
	    // e = true;
	    // window.setTimeout(function() {
	        // var i = $("#plan").width(),
	            // h = $("#plan").height();
	        // if (i === f) {
	            // a(i)
	        // }
	        // e = false
	    // }, b)
	// }
	// $(window).bind("resize", c)
	});

	$(".hightlightmap a").hover(function() {
		var e = $(this).attr("data-item");
		$("area[data-item='"+ e +"']").mapster("set", true, {
			fillColor: "2297c2",
			fillOpacity: 0.6
		})
	}, function() {
		$("area").mapster("set", false);
	}); 