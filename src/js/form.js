// Form
(function () {
	'use strict'
	var forms = document.querySelectorAll('.needs-validation')
	Array.prototype.slice.call(forms)
		.forEach(function (form) {
			form.addEventListener('submit', function (event) {
				if (!form.checkValidity()) {
					event.preventDefault()
					event.stopPropagation()
				}

				form.classList.add('was-validated')
			}, false)
		})
})();

//birthdate
function formatDate(date) {
	var d = new Date(date),
		month = '' + (d.getMonth() + 1),
		day = '' + d.getDate(),
		year = d.getFullYear();

	if (month.length < 2) month = '0' + month;
	if (day.length < 2) day = '0' + day;

	return [year, month, day].join('-');

}

function getAge(dateString) {
	var birthdate = new Date().getTime();
	if (typeof dateString === 'undefined' || dateString === null || (String(dateString) === 'NaN')) {
		birthdate = new Date().getTime();
	}
	birthdate = new Date(dateString).getTime();
	var now = new Date().getTime();
	var n = (now - birthdate) / 1000;
	if (n < 604800) {
		var day_n = Math.floor(n / 86400);
		if (typeof day_n === 'undefined' || day_n === null || (String(day_n) === 'NaN')) {
			return '';
		} else {
			return day_n + '' + (day_n > 1 ? '' : '') + '';
		}
	} else if (n < 2629743) {
		var week_n = Math.floor(n / 604800);
		if (typeof week_n === 'undefined' || week_n === null || (String(week_n) === 'NaN')) {
			return '';
		} else {
			return week_n + '' + (week_n > 1 ? '' : '') + '';
		}
	} else if (n < 31562417) {
		var month_n = Math.floor(n / 2629743);
		if (typeof month_n === 'undefined' || month_n === null || (String(month_n) === 'NaN')) {
			return '';
		} else {
			return month_n + ' ' + (month_n > 1 ? '' : '') + '';
		}
	} else {
		var year_n = Math.floor(n / 31556926);
		if (typeof year_n === 'undefined' || year_n === null || (String(year_n) === 'NaN')) {
			return year_n = '';
		} else {
			return year_n + '' + (year_n > 1 ? '' : '') + '';
		}
	}
}
function getAgeVal(pid) {
	var birthdate = formatDate(document.getElementById("date_of_birth").value);
	var count = document.getElementById("date_of_birth").value.length;
	if (count == '10') {
		var age = getAge(birthdate);
		var str = age;
		var res = str.substring(0, 1);
		if (res == '-' || res == '0') {
			document.getElementById("date_of_birth").value = "";
			document.getElementById("age").value = "";
			$('#date_of_birth').focus();
			return false;
		} else {
			document.getElementById("age").value = age;
		}
	} else {
		document.getElementById("age").value = "";
		return false;
	}
};

//View Profile
$('.view').on('click', function (e) {
	e.preventDefault();
	const href = $(this).attr('href')

	swal({
		title: "View?",
		text: "Do you want to view this data?",
		icon: "warning",
		buttons: true,
		dangerMode: true,
	})
		.then((willDelete) => {
			if (willDelete) {
				document.location.href = href;
			}
		});
})

//Delete Tenant
$('.delete_tenant').on('click', function (e) {
	e.preventDefault();
	const href = $(this).attr('href')

	swal({
		title: "Delete?",
		text: "Do you want to remove this tenant?",
		icon: "warning",
		buttons: true,
		dangerMode: true,
	})
		.then((willDelete) => {
			if (willDelete) {
				document.location.href = href;
			}
		});
})

//Delete Profile
$('.delete').on('click', function (e) {
	e.preventDefault();
	const href = $(this).attr('href')

	swal({
		title: "Delete?",
		text: "Do you want to delete?",
		icon: "warning",
		buttons: true,
		dangerMode: true,
	})
		.then((willDelete) => {
			if (willDelete) {
				document.location.href = href;
			}
		});
})


//Permanent Delete 
$('.permanent_delete').on('click', function (e) {
	e.preventDefault();
	const href = $(this).attr('href')

	swal({
		title: "Delete?",
		text: "Do you want to permanent delete this data?",
		icon: "warning",
		buttons: true,
		dangerMode: true,
	})
		.then((willDelete) => {
			if (willDelete) {
				document.location.href = href;
			}
		});
})

//Delete Profile
$('.delete2').on('click', function (e) {
	e.preventDefault();
	const href = $(this).attr('href')

	swal({
		title: "Delete?",
		text: "Do you want to delete?",
		icon: "warning",
		buttons: true,
		dangerMode: true,
	})
		.then((willDelete) => {
			if (willDelete) {
				document.location.href = href;
			}
		});
})


//Edit Profile
$('.edit').on('click', function (e) {
	e.preventDefault();
	const href = $(this).attr('href')

	swal({
		title: "Edit?",
		text: "Do you want to edit this data?",
		icon: "info",
		buttons: true,
		dangerMode: true,
	})
		.then((willDelete) => {
			if (willDelete) {
				document.location.href = href;
			}
		});
})

//Activate Profile
$('.activate').on('click', function (e) {
	e.preventDefault();
	const href = $(this).attr('href')

	swal({
		title: "Activate?",
		text: "Do you want to activate this data?",
		icon: "info",
		buttons: true,
		dangerMode: true,
	})
		.then((willDelete) => {
			if (willDelete) {
				document.location.href = href;
			}
		});
})

//print
$('.print').on('click', function (e) {
	e.preventDefault();
	const href = $(this).attr('href')

	swal({
		title: "Print?",
		text: "Do you want to print this water analyis report?",
		icon: "info",
		buttons: true,
		dangerMode: true,
	})
		.then((willDelete) => {
			if (willDelete) {
				document.location.href = href;
			}
		});
})

//print
$('.view').on('click', function (e) {
	e.preventDefault();
	const href = $(this).attr('href')

	swal({
		title: "View?",
		text: "Do you want to view this file?",
		icon: "info",
		buttons: true,
		dangerMode: true,
	})
		.then((willDelete) => {
			if (willDelete) {
				document.location.href = href;
			}
		});
})

//remove
$('.remove').on('click', function (e) {
	e.preventDefault();
	const href = $(this).attr('href')

	swal({
		title: "Remove?",
		text: "Do you want to remove this event?",
		icon: "info",
		buttons: true,
		dangerMode: true,
	})
		.then((willDelete) => {
			if (willDelete) {
				document.location.href = href;
			}
		});
})



//Back Profile
$('.back').on('click', function (e) {
	e.preventDefault();
	const href = $(this).attr('href')

	swal({
		title: "Back?",
		text: "Do you want to back?",
		icon: "warning",
		buttons: true,
		dangerMode: true,
	})
		.then((willDelete) => {
			if (willDelete) {
				document.location.href = href;
			}
		});
})


//Save Profile
$('.save').on('click', function (e) {
	e.preventDefault();
	const href = $(this).attr('href')

	swal({
		title: "Save?",
		text: "Do you want to save this job?",
		icon: "warning",
		buttons: true,
		dangerMode: true,
	})
		.then((willDelete) => {
			if (willDelete) {
				document.location.href = href;
			}
		});
})

// Signout
$('.btn-signout').on('click', function (e) {
	e.preventDefault();
	const href = $(this).attr('href')

	swal({
		title: "Signout?",
		text: "Are you sure do you want to signout?",
		icon: "warning",
		buttons: true,
		dangerMode: true,
	})
		.then((willSignout) => {
			if (willSignout) {
				document.location.href = href;
			}
		});
})

//numbers only
$('.numbers').keypress(function (e) {
	var x = e.which || e.keycode;
	if ((x >= 48 && x <= 57) || x == 8 ||
		(x >= 35 && x <= 40) || x == 46)
		return true;
	else
		return false;
});

		// Buttons Profile
		function edit(){
			document.getElementById('Edit').style.display = 'block';
			document.getElementById('password').style.display = 'none';
			document.getElementById('avatar').style.display = 'none';
		}

		function avatar(){
			document.getElementById('avatar').style.display = 'block';
			document.getElementById('Edit').style.display = 'none';
			document.getElementById('password').style.display = 'none';
		}

		function password(){
			document.getElementById('password').style.display = 'block';
			document.getElementById('avatar').style.display = 'none';
			document.getElementById('Edit').style.display = 'none';
		}

		function tenant_profie(){
			document.getElementById('tenant_profile').style.display = 'block';
			document.getElementById('submeter').style.display = 'none';
		}

		function submeter(){
			document.getElementById('submeter').style.display = 'block';
			document.getElementById('tenant_profile').style.display = 'none';
		}

//image preview
function previewImage(event) {
	var input = event.target;
	var reader = new FileReader();

	reader.onload = function() {
		var imgElement = document.getElementById("poster-preview");
		imgElement.src = reader.result;
		imgElement.style.display = "block";
	};

	reader.readAsDataURL(input.files[0]);
}

// //clear modal iput fields original
// document.addEventListener('DOMContentLoaded', function () {
// 	const closeButton = document.getElementById('closeButton');
// 	const form = document.querySelector('.needs-validation');

// 	closeButton.addEventListener('click', function () {
// 		form.reset(); // Reset the form fields
// 	});
// });

// //clear modal iput fields new
document.addEventListener('DOMContentLoaded', function () {
    const closeButton = document.getElementById('closeButton');
    const form = document.querySelector('.needs-validation');

    if (closeButton && form) { // Make sure both exist
        closeButton.addEventListener('click', function () {
            form.reset(); // Reset the form fields
        });
    }
});
