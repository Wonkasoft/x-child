( function($) 
{
	"use strict";

/*===========================================================
=            Strict Functions that can be called            =
===========================================================*/
	// For created url copy to clipboard
	function copy_to_clipboard( element ) 
	{
		/* setting up and executing copy command */
		var $temp = $( "<input>" );
		$( "body" ).append( $temp );
		$temp.val($( element).text()).select();
		document.execCommand( "copy" );
		$temp.remove();

		/* Print copy confimation in tooltip */
		var tooltip_id = document.querySelector( '.affiliate-copy-btn-wrap > a').getAttribute( 'aria-describedby' );
		document.querySelector( '#' + tooltip_id + ' .tooltip-inner' ).innerText = 'Affiliate link has been copied!';
	}

	/* This function is for making element adjustments for the welcome page */
	function adjust_welcome_page() 
	{
		if ( document.querySelector( '#welcome-vr-icon' ) && document.querySelector( '#welcome-mp-icon' ) && document.querySelector( '#welcome-profile-icon' ) ) 
		{
			var wel_vr = document.querySelector( '#welcome-vr-icon' ).offsetTop, 
			wel_mp = document.querySelector( '#welcome-mp-icon' ).offsetTop, 
			wel_pro = document.querySelector( '#welcome-profile-icon' ).offsetTop,
			wel_icon_modules = document.querySelectorAll( '.icon-module' ),
			set_height = 0;

			/* setting icon modules heights on welcome page */
			wel_icon_modules.forEach( function( el, i )
				{
					
					/* checking height var */
					if ( set_height < el.offsetHeight ) 
					{
						/* setting height var */
						set_height = el.offsetHeight;
					}

					/* checking element height */
					if ( set_height >= el.offsetHeight ) 
					{
						/* setting current element height */
						el.style.height = set_height + 'px';

						/* checking previous element height */
						if ( i != 0 && wel_icon_modules[i-1].offsetHeight < set_height ) 
						{
							/* fixing previous element height */
							wel_icon_modules[i-1].style.height = set_height + 'px';
						}
					}
				});
			
			/* adjusting the html tag to remove scroll on desktop */
			if ( wel_vr == wel_mp && wel_mp == wel_pro ) 
			{
				/* adjusting the html tags for admin bar offset */
				var admin_bar_height = document.getElementById( 'wpadminbar' ).offsetHeight;
				document.querySelector('html').style.height = "calc( 100% - " + admin_bar_height + "px )";

				/* checking for bg fixed element */
				if ( document.querySelector('.backstretch') ) 
				{
					/* adjusting bg fixed element */
					document.querySelector('.backstretch').style.width = window.innerWidth + 'px';
					document.querySelector('.backstretch>img').style.width = window.innerWidth + 'px';
				}
			} 
			else 
			{
				console.log('removed');
				/* removing html tags style */
				document.querySelector('html').removeAttribute( 'style' );
			}

			/* checking for bg fixed element */
			if ( document.querySelector('.backstretch') ) 
			{
				/* adjusting bg fixed element */
				document.querySelector('.backstretch').style.width = window.innerWidth + 'px';
				document.querySelector('.backstretch>img').style.width = window.innerWidth + 'px';
			}
		}
	}
/*=====  End of Strict Functions that can be called  ======*/

/*=====================================================
=            To run when window is resized            =
=====================================================*/
	window.onresize = function() 
	{
		/* checking for admin bar element and welcome row */
		if ( document.getElementById( 'wpadminbar' ) && document.getElementById( 'welcome-icon-row' ) ) 
		{
			/* call function for adjusting elements on welcome page */
			adjust_welcome_page();
		}
	};
/*=====  End of To run when window is resized  ======*/


/*============================================================
=            To run when document is fully loaded            =
============================================================*/
	window.onload = function() 
	{
		/* checking for copy affiliate link btn */
		if ( $( 'div.affiliate-copy-btn-wrap' ).length ) 
		{
			// This calls the copy to clipboard function when the clipboard button is clicked.
			$( 'div.affiliate-copy-btn-wrap' ).click( function () 
			{
				/* call copy command to copy link */
				copy_to_clipboard( 'a.affiliate-copy-link' );
			});
		}

		/* checking for copy affiliate link btn */
		if ( $( 'a.affiliate-copy-link' ).length ) 
		{
			// This calls the copy to clipboard function when the link is clicked.
			$( 'a.affiliate-copy-link' ).click( function () 
			{
				/* call copy command to copy link */
				copy_to_clipboard( 'a.affiliate-copy-link' );
			});
		}

		/* checking for vr room modal */
		if ( $( '#virtual-modal' ).length ) 
		{
			/* setting click event for vr room modal */
			$( '.vr-room-form-btn a' ).click( function() 
			{
				$( '#virtual-modal' ).css({ 'left': '0', 'right': '0', 'opacity': '1'});
				$( '#virtual-modal' ).css({ 'left': '0', 'right': '0'});
				$( '#virtual-modal' ).delay( 800 ).css({ 'opacity': '1'});
				$( '.virtual-modal-bg' ).css({ 'background': 'rgba( 0, 0, 0, .97 )'});
			});

			/* setting click event for vr room modal close */
			$( 'a.virtual-modal-close' ).click( function() 
			{
				$( '#virtual-modal' ).css({ 'left': '-15px', 'right': '102%', 'opacity': '0'});
				$( '.virtual-modal-bg' ).removeAttr( 'style' );
				$( '#virtual-modal' ).css({ 'opacity': '0'});
				$( '#virtual-modal' ).delay( 800 ).css({ 'left': '-15px', 'right': '102%'});
			});

		}

		/* checking for bp menu */
		if ( $( '#menu-bp' ).length ) 
		{
			/* loading vars to append bp menu */
			var $profile_menu = $( '#menu-bp'),
			$menu_targets = $profile_menu.find( '.sub-menu').parent(),
			$menu_subs = $profile_menu.find( '.sub-menu');

			/* appending bp menu with carrots for sub-menus */
			$menu_targets.each(function(i) 
			{
				$(this).append('<div class="x-sub-toggle" data-toggle="collapse" data-target=".sub-menu.sm-' + i + '"><span><i class="x-icon-angle-double-down" data-x-icon="&#xf103;"></i></span></div>'); 
			});		
		}

		/* checking for welcome page profile button element  */
		if ( $( '.bp-profile-nav' ).length && $( '#welcome-profile-anchor-button' ).length ) 
		{
			/* setting profile button link  */
			var $profile_link = $( '.bp-profile-nav' ).attr( 'href' );
			$( '#welcome-profile-anchor-button' ).attr( 'href', '/members' );
		}

		/* checking for admin bar element and welcome row */
		if ( document.getElementById( 'wpadminbar' ) && document.getElementById( 'welcome-icon-row' ) ) 
		{
			/* call function for adjusting elements on welcome page */
			adjust_welcome_page();
		}

		/* checking for welcome page elements */
		if ( document.querySelector( '#welcome-vr-icon' ) && document.querySelector( '#welcome-mp-icon' ) && document.querySelector( '#welcome-profile-icon' ) ) 
		{
			/* setting for welcome page element links */
			var wel_pro_link = document.querySelector( '#welcome-profile-anchor-button' ).href;
			document.querySelector( '#profile-icon-link' ).href = wel_pro_link;
			var wel_mp_link = document.querySelector( '#welcome-build-anchor-button' ).href;
			document.querySelector( '#market-icon-link' ).href = wel_mp_link;
			var wel_vr_link = document.querySelector( '#welcome-inspire-anchor-button' ).href;
			document.querySelector( '#vr-icon-link' ).href = wel_vr_link;

			/* setting for welcome page profile icon click events */
			document.querySelector( '#welcome-profile-icon' ).addEventListener( 'click', function() 
			{
				window.location.href = wel_pro_link;
			} );

			/* setting for welcome page market place icon click events */
			document.querySelector( '#welcome-mp-icon' ).addEventListener( 'click', function() 
			{
				window.location.href = wel_mp_link;
			} );

			/* setting for welcome page vr icon click events */
			document.querySelector( '#welcome-vr-icon' ).addEventListener( 'click', function() 
			{
				window.location.href = wel_vr_link;
			} );
		}
	};
/*=====  End of To run when document is fully loaded  ======*/

})(jQuery);