$(function() {

	var sidebarLogic = function() {

		//save this as root
		var root = this;

		//save the dom elements
		root.mainSidebar = $('#main_sidebar');
		root.searchInputWrapper = $('div.search', '#main_sidebar');
		root.searchInput = $('input:text', '#main_sidebar');
		root.searchlabel = $('label', root.searchInputWrapper);

		//input wrap hover handler
		root.searchHoverHandler = function(action) {

			//on hover
			root.searchInputWrapper.hover(function(event) {

				//remove the old active class and add the inactive class
				root.searchInputWrapper.addClass('active').addClass('hover');

			},
			                             function() {

				                             //remove the old active class and add the inactive class
				                             root.searchInputWrapper.removeClass('hover');
				                             if (root.searchInputWrapper.hasClass('focus') === false) {
					                             root.searchInputWrapper.removeClass('active');
				                             }

			                             });

		}

		//input wrap focus handler
		root.searchFocusHandler = function() {

			if (root.searchInput.val() === '') {
				//make the search box label visible
				root.searchlabel.show();
			}

			root.searchInput.focus(function() {

				//remove the old active class and add the inactive class
				root.searchInputWrapper.addClass('active').addClass('focus');

				//hide the label
				root.searchlabel.hide();

			});

			root.searchInput.blur(function() {

				//remove the old active class and add the inactive class
				root.searchInputWrapper.removeClass('focus');
				if (root.searchInputWrapper.hasClass('hover') === false) {
					root.searchInputWrapper.removeClass('active');
				}

				//if the input is empty then show the label
				if (root.searchInput.val() === '') {
					root.searchlabel.show();
				}

			});

		};

		//define the constructor
		root.construct = function() {

			//on search hover event
			root.searchHoverHandler();

			//on search focus event
			root.searchFocusHandler();

		}

		//self execute
		root.construct();

	}

	new sidebarLogic;

});