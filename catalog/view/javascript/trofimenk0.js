$(document).ready(function () {

	if($(window).width() < 768) {
		window.onscroll = function () {
			if (
			document.body.scrollTop > 20 ||
			document.documentElement.scrollTop > 20
			) {
			document.getElementsByTagName('header')[0].classList.add("scrolling");
			} else {
			document.getElementsByTagName('header')[0].classList.remove("scrolling");
			}
		};
	}


	// trofimenk0 partners form

	$('#partnerRequestForm').submit(function (e) {
		//отмена действия по умолчанию для кнопки submit
		e.preventDefault();

		let form = $(this);

		let formFields = form.find(':input');
		let validFlag = true;

		formFields.each(function () {
			if ($(this).attr('id') == 'partners-email' || $(this).attr('id') == 'partners-trade_duration' || $(this).val() != '') {
				$(this).css('border-color', '#1fc0a0');
			} else {
				$(this).css('border-color', 'red');
				validFlag = false;
			}
		});

		if (validFlag) {
			$.ajax({
				type: form.attr('method'),
				url: form.attr('action'),
				data: form.serialize()
			}).done(function (json) {
				console.log(json);
				$('#partners .inner .success').css('display', 'flex').hide().fadeIn();
				setTimeout(function () {
					form.trigger("reset");
					formFields.each(function () {
						$(this).css('border-color', '#eee');
					});
					$('#partners .inner .success').fadeOut();
				}, 2000);
			}).fail(function () {
				console.log('fail');
			});
		}
	});

	$('#partnersPage .buttonPartnersModal').on('click', function (e) {
		$('#partnersPage + #partners').css('display', 'flex').hide().fadeIn();
	});

	$('#partnersPage + #partners').on('click', function (event) {
		if ($(event.target).hasClass('inner')) {
			$('#partnersPage + #partners').fadeOut();
		}
	});
	// trofimenk0 partners form



	// trofimenk0
	$('#buttonModalForm').on('click', function (e) {
		$('#headerFeedbackForm').css('display', 'flex').hide().fadeIn();
	});

	$('#headerFeedbackForm').on('click', function (e) {
		if (e.target.id == this.id) {
			$('#headerFeedbackForm').fadeOut();
		}
	});

	$('.feedbackForm').submit(function (e) {
		//отмена действия по умолчанию для кнопки submit
		e.preventDefault();

		let form = $(this);
		let formFields = form.find(':input');

		let validFlag = true;

		formFields.each(function () {
			if ($(this).val() != '') {
				$(this).css('border-color', '#1fc0a0');
			} else {
				$(this).css('border-color', 'red');
				validFlag = false;
			}
		});

		if (validFlag) {

			$.ajax({
				type: form.attr('method'),
				url: form.attr('action'),
				data: form.serialize()
			}).done(function (json) {
				$('.feedbackFormSection .inner .success').css('display', 'flex').hide().fadeIn();

				setTimeout(function () {
					form.trigger("reset");
					$('.feedbackFormSection .inner .success').fadeOut();
				}, 2000);

			}).fail(function () {
				console.log('fail');
			});
		} else {
			$.each(formFields, function (index, item) {
				if (item.val() == "") {
					item.css('border-color', 'red');
				} else {
					item.css('border-color', '#1fc0a0');
				}
			});
		}

	});
	// trofimenk0



	// trofimenk0 submenu fix
	if($(window).width() > 767) {
		$("#menu .dropdown-subinner").mouseenter(function () {
			$(this).find('ul').fadeIn();
		}).mouseleave(function () {
			$(this).find('ul').fadeOut();
		});
	}

	
	if($(window).width() < 768) {
		$("#menu .dropdown-subinner a").on("click", function(event) {
			event.preventDefault();
			var target = $( event.target );
			if(target.is('a')){
				window.location.href = target.attr('href');
			} else {
				target.parents('.dropdown-subinner').find('ul').slideToggle();
				event.stopImmediatePropagation();
			}
		});
	}
	

	// trofimenk0 submenu fix


	// trofimenk0 cart order validation modal

	if($('#modalNotValidOrder').length)	{
		$('#modalNotValidOrder').css('display', 'flex').hide().fadeIn();
		setTimeout(function () {
			$('#modalNotValidOrder').fadeOut();
		}, 2000);
	}

	$("#buttonNotValidOrder").on('click', function(){
		$('#modalNotValidOrder').css('display', 'flex').hide().fadeIn();
		setTimeout(function () {
			$('#modalNotValidOrder').fadeOut();
		}, 1000);
	});

	// trofimenk0 cart order validation modal



	// phone mask
	$('.feedbackForm .feedback-phone, #partners-phone, #formContact #input-phone')

	.keydown(function (e) {
		var key = e.which || e.charCode || e.keyCode || 0;
		$phone = $(this);

    // Don't let them remove the starting '('
    if ($phone.val().length < 7 && (key === 8 || key === 46)) {
			$phone.val('+38 (0'); 
      return false;
	}
    // Reset if they highlight and type over first char.
    else if ($phone.val().charAt(4) !== '(') {
			$phone.val('('+String.fromCharCode(e.keyCode)+''); 
		}

		// Auto-format- do not expose the mask as the user begins to type
		if (key !== 8 && key !== 9) {
			if ($phone.val().length === 8) {
				$phone.val($phone.val() + ')');
			}
			if ($phone.val().length === 9) {
				$phone.val($phone.val() + ' ');
			}			
			if ($phone.val().length === 12 || $phone.val().length === 15) {
				$phone.val($phone.val() + '-');
			}
		}

		// Allow numeric (and tab, backspace, delete) keys only
		return (key == 8 || 
				key == 9 ||
				key == 46 ||
				(key >= 48 && key <= 57) ||
				(key >= 96 && key <= 105));	
	})
	
	.bind('focus click', function () {
		$phone = $(this);
		
		if ($phone.val().length === 3) {
			$phone.val('(');
		}
		else {
			var val = $phone.val();
			$phone.val('').val(val); // Ensure cursor remains at the end
		}
	})
	
	.blur(function () {
		$phone = $(this);
		
		if ($phone.val() === '(') {
			$phone.val('');
		}
	});
	// phone mask

});


