// Load all recipe
function loadAllRecipe() {
	$recipePanelTemplate = $("#recipePanelTemplate");
	$recipeEmptyPanel = $("#recipeEmptyPanel");
	$recipeContent = $("#recipeContent");
	$.get("getrecipelist.php", {"list": "all"}, function(data) {
		$recipeContent.empty();
		if(data.length > 0) {
			$.each(data, function(i, v) {
				$recipePanelItem = $recipePanelTemplate.clone(true);
				$recipePanelItem.attr("id", "recipePanel" + v.id);
				$recipePanelItem.find(".panel-title").html('<a href="recipe.html?id=' + v.id + '" target="_blank">' + v.name + "</a>");
				$recipePanelItem.find(".panel-body img").attr("src", "viewrecipeimage.php?id=" + v.id);
				$recipePanelItem.find(".panel-body a").attr("href", "viewrecipeimage.php?id=" + v.id);
				$recipePanelItem.find(".panel-body a").magnificPopup({
					type: "image",
					items: {
						src: $recipePanelItem.find(".panel-body img").attr("src")
					},
					removalDelay: 300,
					mainClass: 'mfp-with-fade',
					closeOnContentClick: true
				});
				$recipePanelItem.find(".col-md-9").html(v.description);
				$recipePanelItem.fadeIn();
				$recipeContent.append($recipePanelItem);
			});
		} else {
			$recipePanelItem = $recipeEmptyPanel.clone(true);
			$recipePanelItem.show();
			$recipeContent.append($recipePanelItem);
		}
	}, "json");
}

// Load my recipe
function loadMyRecipe() {
	$recipePanelTemplate = $("#recipePanelTemplate");
	$recipeEmptyPanel = $("#recipeEmptyPanel");
	$recipeContent = $("#recipeContent");
	$.get("getrecipelist.php", {"list": "mine"}, function(data) {
		$recipeContent.empty();
		if(data.length > 0) {
			$.each(data, function(i, v) {
				$recipePanelItem = $recipePanelTemplate.clone(true);
				$recipePanelItem.attr("id", "recipePanel" + v.id);
				$recipePanelItem.find(".panel-title").html('<a href="recipe.html?id=' + v.id + '" target="_blank">' + v.name + "</a>");
				$recipePanelItem.find(".panel-body img").attr("src", "viewrecipeimage.php?id=" + v.id + "&nocache=" + (new Date()).getTime());
				$recipePanelItem.find(".panel-body a").attr("href", "viewrecipeimage.php?id=" + v.id);
				$recipePanelItem.find(".panel-body a").magnificPopup({
					type: "image",
					items: {
						src: $recipePanelItem.find(".panel-body img").attr("src")
					},
					removalDelay: 300,
					mainClass: 'mfp-with-fade',
					closeOnContentClick: true
				});
				$recipePanelItem.find(".col-md-9").html(v.description);
				$recipePanelItem.fadeIn();
				$recipeContent.append($recipePanelItem);
			});
		} else {
			$recipePanelItem = $recipeEmptyPanel.clone(true);
			$recipePanelItem.show();
			$recipeContent.append($recipePanelItem);
		}
	}, "json");
}