/* global window */
( function( window, $, undefined ) {
	var document = window.document,
		ace = window.ace,
		UTILS = window.UTILS,

	ADMIN = {

		common: {

			// Application-wide code.
			init: function() {
				ADMIN.utils.handleSortables();
				ADMIN.utils.handleSuggestions();
				ADMIN.utils.handleTagsSuggestions();
				ADMIN.utils.initializeMarkdonEditors();
				ADMIN.utils.handleGalleryImagesUpload();
				ADMIN.utils.handleCategoriesSuggestions();

				// Form hints.
				$( ".help-hint" ).each( function() {
					var $this = $( this ),
						$field = $this.closest( ".form-group" ).find( ".form-control" ),
						title = $this.data( "title" ) || "",
						content = $this.html(),
						popoverOptions = {
							html: true,
							trigger: "focus",
							title: title,
							content: content
						};

					$field.popover( popoverOptions );
				} );

				UTILS.leaving_unsaved_page_checker.init();
			}
		},

		cards: {
			edit: function() {
				ADMIN.utils.handleCardToCategories();
			}
		},

		utils: {

			initializeMarkdonEditors: function() {

				// Markdown Editor requires Ace
				ace.config.set( "basePath", "/public/admin/dist/scripts/ace/" );
				$.each( $( "textarea[data-provide=markdown]" ), function( i, el ) {
					$( el ).markdownEditor( {
						preview: true,
						onPreview: function( content, callback ) {
							var lang = $( "html" ).attr( "lang" );
							$.ajax( {
								type: "POST",
								url: "/api/" + lang + "/markdown/transform/",
								data: {
									source: content
								},
								success: function( output ) {
									callback( output );
								}
							} );
						}
					} );
				} );
			},

			handleGalleryImagesUpload: function() {
				
				$( ".js--image_to_gallery_link" ).each( function() {

					var $link = $( this );
					var $wrap = $link.closest(".js--image_gallery_wrap");

					$link.hide();

					var url = $link.attr( "href" ),
						$progress = $wrap.find( ".progress-bar" ),
						$msg = $wrap.find( ".img-message" ),
						$list = $wrap.find( ".list-group-images" ),
						$input = $( "<input>", {
							"type": "file",
							"name": "files[]",
							"data-url": url,
							"multiple": "multiple"
						} );

					$input.insertBefore( $link );

					$input.fileupload( {
						dataType: "json",
						multipart: false,
						start: function() {
							$progress.show();
						},
						progressall: function( e, data ) {
							var progress = parseInt( data.loaded / data.total * 100, 10 );

							$progress.css(
								"width",
								progress + "%"
							);
						},
						done: function( e, data ) {

							// This is the same grip like in handleSortables
							var glyph = "<span class='fas fa-grip-vertical text-secondary handle pr-3' " +
								" title='sorting'></span>";

							$( data.result.image_gallery_item )
								.addClass( "not-processed" )
								.prepend( glyph )
								.appendTo( $list );

							$list.sortable( "refresh" );

							$msg.remove();
						},
						stop: function() {
							$list.find( ".not-processed" )
								.prepend( "<span class='glyphicon glyphicon-align-justify'></span>" )
								.removeClass( "not-processed" );

							$progress.hide().css(
								"width",
								"0"
							);
						}
					} );

				} );
			},

			handleFormErrors: function( errors ) {
				$.each( errors, function( field, errorList ) {
					var $field = $( "#id_" + field ),
						$fg = $field.closest( ".form-group" ),
						errorMsgs = [];

					// Add proper class for error styling.
					$fg.addClass( "has-error" );

					// Prepeare error messages list.
					errorMsgs.push( "<ul class='help-block help-block-errors'>" );
					$.each( errorList, function( i, errorMsg ) {
						errorMsgs.push( "<li>" + errorMsg + "</li>" );
					} );
					errorMsgs.push( "</ul>" );

					// Insert error messages list into form.
					$( errorMsgs.join( "" ) ).insertAfter( $field );
				} );
			},

			clearErrorMessages: function( $form ) {
				$form.find( ".has-error" ).removeClass( "has-error" );
				$form.find( ".help-block-errors" ).remove();
			},

			handleCardToCategories: function() {
				var $form = $( "#form_cards_add_to_category" ),
					$input = $( "#id_category" ),
					$categies = $( "#categies" );

				$form.on( "ajax:success", function( jqEv, json ) {
					ADMIN.utils.clearErrorMessages( $form );
					$categies.html( json.snippet );
					ADMIN.utils.handleSortables( $categies.find( ".list-sortable" ) );

					if ( !json.hasErrors ) {
						$input.val( "" );
					} else {
						ADMIN.utils.handleFormErrors( json.errors );
					}

					$input.focus().select();
				} );
			},

			// ADMIN.utils.handleFormErrors();
			// ADMIN.utils.handleFormErrors( ".list-sortable" );
			// ADMIN.utils.handleFormErrors( $element.find( "ul" ) );
			handleSortables: function( sortable ) {

				// Sortable lists.
				if ( sortable === undefined ) {
					$sortable = $( ".list-sortable" );
				} else {
					$sortable = $( sortable );
				}

				var $sortable = $( ".list-sortable" ),
					glyph = "<span class='fas fa-grip-vertical text-secondary handle pr-3' " +
						" title='sorting'></span>",
					url, $item, data, $list, id;

				if ( $sortable.length ) {
					$sortable.find( ".list-group-item" ).prepend( glyph );

					$sortable.sortable( {
						cancel: "strong",
						handle: ".handle",
						opacity: 0.9,
						update: function( jqEv, ui ) {
							$item = $( ui.item );
							$list = $item.closest( ".list-sortable" );
							url = $list.data( "sortable-url" );
							id = $list.data( "sortable-param" ) || "id";
							data = {
								rank: $item.index()
							};
							data[ id ] = $item.data( "id" );

							$.ajax( {
								type: "POST",
								url: url,
								data: data,
								success: function() {
								},
								error: function() {
								}
							} );
						}
					} );
				}
			},

			// Suggests anything according by an url
			handleSuggestions: function() {
				$( document ).on( "keyup.autocomplete", "[data-suggesting='yes']", function(){
					$( this ).autocomplete( {
						source: function( request, response ) {
							var $el = this.element,
								url = $el.data( "suggesting_url" ),
								term;
							term = request.term;

							$.getJSON( url, { q: term }, function( data ) {
								response( data );
							} );
						}
					} );
				} );
			},

			categoriesSuggest: function( selector ) {
				var $input = $( selector ),
					url = $input.data( "suggesting_url" ),
					cache = {},
					term;

				if ( !$input.length ) {
					return;
				}

				$input.autocomplete( {
					minLength: 1,
					source: function( request, response ) {
						term = request.term;

						if ( term in cache ) {
							response( cache[ term ] );
						} else {
							$.getJSON( url + term, function( data ) {
								cache[ term ] = data;
								response( data );
							} );
						}
					},
					search: function() {
						term = this.value;

						if ( term.length < 1 ) {
							return false;
						}
					},
					focus: function() {
						return false;
					},
					select: function( event, ui ) {
						this.value = ui.item.value;
						return false;
					}
				} );
			},

			// Suggests tags
			handleTagsSuggestions: function() {
				$( document ).on( "keyup.autocomplete", "[data-tags_suggesting='yes']", function() {
					var $input = $( this ),
						lang = $( "html" ).attr( "lang" ),
						url = "/api/" + lang + "/tags_suggestions/?format=json&q=",
						cache = {},
						term, terms;

					function split( val ) {
						return val.split( /,\s*/ );
					}
					function extractLast( t ) {
						return split( t ).pop();
					}

					if ( !$input.length ) {
						return;
					}

					$input.autocomplete( {
						minLength: 1,
						source: function( request, response ) {
							term = extractLast( request.term );

							if ( term in cache ) {
								response( cache[ term ] );
							} else {
								$.getJSON( url + term, function( data ) {
									cache[ term ] = data;
									response( data );
								} );
							}
						},
						search: function() {
							term = extractLast( this.value );

							if ( term.length < 1 ) {
								return false;
							}
						},
						focus: function() {
							return false;
						},
						select: function( event, ui ) {
							terms = split( this.value );
							terms.pop();
							terms.push( ui.item.value );
							terms.push( "" );
							this.value = terms.join( " , " );
							return false;
						}
					} );
				} );
			},

			handleCategoriesSuggestions: function() {
				ADMIN.utils.categoriesSuggest( "[data-suggesting_categories='yes']" );
			}
		}
	};

	/*
	 * Garber-Irish DOM-based routing.
	 * See: http://goo.gl/z9dmd
	 */
	ADMIN.INITIALIZER = {
		exec: function( controller, action ) {
			var ns = ADMIN,
				c = controller,
				a = action;

			if ( a === undefined ) {
				a = "init";
			}

			if ( c !== "" && ns[ c ] && typeof ns[ c ][ a ] === "function" ) {
				ns[ c ][ a ]();
			}
		},

		init: function() {
			var body = document.body,
			controller = body.getAttribute( "data-controller" ),
			action = body.getAttribute( "data-action" );

			ADMIN.INITIALIZER.exec( "common" );
			ADMIN.INITIALIZER.exec( controller );
			ADMIN.INITIALIZER.exec( controller, action );
		}
	};

	// Expose ADMIN to the global object.
	window.ADMIN = ADMIN;

	// Initialize application.
	ADMIN.INITIALIZER.init();
} )( window, window.jQuery );
