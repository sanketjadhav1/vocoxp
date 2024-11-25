<?php

include_once '../connection.php';

// error_reporting(E_ALL & ~E_DEPRECATED);

// ini_set('display_errors', 1);

// Now you can use the connection class

$connection = connection::getInstance();

$mysqli = $connection->getConnection();



$connection1 = database::getInstance();

$mysqli1 = $connection1->getConnection();
// echo "post";
// print_r($_POST);
// echo "get";
// print_r($_GET);
if ($_SERVER["REQUEST_METHOD"] == "GET") {
	$agency_id = $_GET['agency_id'];

	$request_no = $_GET['request_no'];

	$bulk_id = $_GET['bulk_id'];



	$fetch_agency = "SELECT `company_name`, `address`, `mobile_no` FROM `agency_header_all` WHERE `agency_id`='$agency_id'";

	$res_agency = mysqli_query($mysqli, $fetch_agency);

	$arr_agency = mysqli_fetch_assoc($res_agency);

	$otp = rand(10000, 99999);
	// $send_otp=sms_helper_accept($arr_agency['mobile_no'], $otp);





	$fetch_data = "SELECT * FROM `bulk_weblink_request_all` WHERE `agency_id`='$agency_id'";

	$res_data = mysqli_query($mysqli, $fetch_data);


	while ($arr_data = mysqli_fetch_assoc($res_data)) {
		$arr_all[] = $arr_data;
	}
}
// $verification = explode(",", $arr_data['verifications']);


$fetch_verification = "SELECT * FROM `verification_configuration_all` WHERE `ver_type`='1'";
$res_verificaton = mysqli_query($mysqli1, $fetch_verification);
while ($arr_verification = mysqli_fetch_assoc($res_verificaton)) {

	$arr_veri[] = $arr_verification;
}
$jsonArrVeri = json_encode($arr_veri);
// print_r($arr_veri);
// exit;







?>
<!DOCTYPE html>
<html>

<head>

	<meta charset="utf-8">

	<title>Micro Integrated </title>

	<!-- Mobile Specific Metas -->

	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<!-- Font-->

	<link rel="stylesheet" type="text/css" href="css/opensans-font.css">

	<link rel="stylesheet" type="text/css" href="css/roboto-font.css">

	<link rel="stylesheet" type="text/css" href="fonts/material-design-iconic-font/css/material-design-iconic-font.min.css">

	<!-- datepicker -->

	<link rel="stylesheet" type="text/css" href="css/jquery-ui.min.css">

	<!-- Main Style Css -->

	<link rel="stylesheet" href="css/style.css" />

	<script>
		document.addEventListener("DOMContentLoaded", function() {
			var today = new Date().toISOString().split('T')[0];
			document.querySelector('input[type="date"]').setAttribute('min', today);
		});
	</script>
	<style>
		/* General Styles */
		.inner {
			padding: 20px;
		}

		.table-container {
			margin-bottom: 20px;
		}

		h3 {
			font-size: 1.5em;
			margin-bottom: 10px;
		}

		p {
			margin-bottom: 20px;
			font-size: 1em;
			color: #555;
		}

		/* Table Styles */
		.table {
			width: 100%;
			border-collapse: collapse;
		}

		.table-bordered {
			border: 1px solid #ddd;
		}

		.table th,
		.table td {
			padding: 12px;
			text-align: left;
			border: 1px solid #ddd;
		}

		.table th {
			background-color: #f4f4f4;
			font-weight: bold;
		}

		.table tbody tr:nth-child(even) {
			background-color: #f9f9f9;
		}

		.table tbody tr:hover {
			background-color: #f1f1f1;
		}

		/* Responsive Styles */
		@media (max-width: 768px) {
			.table {
				font-size: 0.9em;
			}
		}

		/* Basic Reset */
		.otp-card {
			width: 300px;
			padding: 20px;
			background-color: #fff;
			border-radius: 8px;
			margin: 0 auto;
			/* Center align */
			box-shadow: none;
			/* Removed box shadow */
			background-image: url('images/wizard-v4.jpg');
		}

		.otp-container {
			text-align: center;
		}

		.otp-container h2 {
			font-size: 24px;
			margin-bottom: 10px;
			color: #333;
		}

		.otp-container p {
			font-size: 14px;
			color: #666;
			margin-bottom: 20px;
		}

		.otp-input {
			width: 92%;
			padding: 10px;
			font-size: 16px;
			margin-bottom: 20px;
			border: 1px solid #ddd;
			border-radius: 4px;
			text-align: center;
		}

		.otp-button {
			width: 100%;
			padding: 10px;
			font-size: 16px;
			background-color: #007bff;
			color: #fff;
			border: none;
			border-radius: 4px;
			cursor: pointer;
		}

		.otp-button:hover {
			background-color: #0056b3;
		}
	</style>

</head>

<body>
	<section class="otp-card">
		<div class="otp-container">
			<h2>Validate Your Number</h2>
			<p>We have sent an OTP to your registered mobile number.</p>
			<h4><?php echo $arr_agency['mobile_no'] ?></h4><br>

			<button type="submit" value="send_otp" class="otp-button" id="submitOtp">Send OTP</button>

			<!-- OTP Input Field (hidden initially) -->
			<input type="text" class="otp-input" id="userOtp" placeholder="Enter OTP" style="display:none; margin-top: 10px;">

			<!-- Verify Button (hidden initially) -->
			<button type="submit" class="otp-button" id="verifyOtp" style="display:none;">Verify OTP</button>
		</div>

	</section>
	<br>

	<div class="page-content" style="background-image: url('images/wizard-v4.jpg'); display:none">

		<div class="wizard-v4-content">

			<div class="wizard-form">

				<div class="wizard-header">

					<h3 class="heading">Welcome <b><i><?php echo $arr_agency['company_name']; ?></b></i></h3>

					<form id="requestForm" method="post">
						<label for="request-no"><b>Request No:- </b></label>
						<select name="request_no" id="request-no" class="form-control">
							<?php foreach ($arr_all as $val) { ?>
								<option value="<?php echo $val['request_no']; ?>" data-generated-for="<?php echo $val['generated_for']; ?>">
									<?php echo $val['request_no']; ?>
								</option>
							<?php } ?>
						</select><br />
						<input type="hidden" name="agency_id" id="agency_id" value="<?php echo $agency_id; ?>">
						<input type="hidden" name="bulk_id" id="bulk_id" value="<?php echo $bulk_id; ?>">

					</form>
					<label for="verification-for"><b>Verification For:- </b><span id="verification-for"></span></label><br />


					<label for="address"><b>Address: 202, Cybernex, shankar seth road, near swargat bus depo. 411037, Pune, Maharashtra</b></label>

					<!--<label for="address"><b>Address: <?php echo $arr_agency['address'] ?></b></label>-->

					<!--<p><b><?php echo $arr_agency['company_name']; ?></b> Agency</p>-->

				</div>

				<form id="uploadForm" class="form-register" method="post" enctype="multipart/form-data">

					<!--<form class="form-register" action="#" method="post">-->

					<div id="form-total">

						<!-- SECTION 1 -->

						<h2>

							<span class="step-icon"><i class="zmdi zmdi-upload"></i></span>

							<span class="step-text">Upload</span>

						</h2>

						<section>

							<div class="inner">
								<h4>Verifications</h4>

								<div class="row" id="card_01">

								</div>

								<h4>Upload Excel containg the data </h4>



								<div class="form-row">

									<div class="form-holder">

										<label class="form-row-inner">


											<input type="file" id="upload-excel" class="form-control" name="upload_excel" onchange="updateFileName()" required>

											<!--<span class="label">First Name</span>-->

											<!--<span class="border"></span>-->

										</label>

									</div>



								</div>





							</div>

						</section>

						<!-- SECTION 2 -->

						<h2>

							<span class="step-icon"><i class="zmdi zmdi-settings"></i></span>

							<span class="step-text">Configurations</span>

						</h2>

						<section>
							<div class="inner">
								<form id="uploadForm" enctype="multipart/form-data" style="display:none;">
									<h4>Uploaded Excel File</h4>
									<input type="text" id="file-name" name="upload_excel" class="form-control" placeholder="Enter file name" readonly>
									<input type="submit" value="Start Analyze" class="btn btn-primary mt-2">
									<br>
									<input type="hidden" name="agency_id" id="agency_id" value="<?php echo $agency_id; ?>">
									<input type="hidden" name="bulk_id" id="bulk_id" value="<?php echo $bulk_id; ?>">
								</form>
								<div id="response" class="alert" style="display: none;"></div>

								<br>

								<!-- Input fields -->
								<input type="hidden" id="file_name_01" name="upload" class="form-control" placeholder="Enter file name" readonly>

								<h4 style="margin-top: 10px;">Amount to be paid by:</h4>
								<div id="radio">
									<input type="radio" name="amount" id="1" value="agency_wallet" onclick="toggleGridVisibility()"> <label for="1">Agency Wallet</label>
									<input type="radio" name="amount" id="2" value="end_user"> <label for="2" onclick="toggleGridVisibility1()">By End User</label>
								</div>

								<br>

								<div class="form-row">
									<div class="form-holder form-holder-2">
										<label class="form-row-inner">
											<h4>Weblink Activated From</h4>
											<input type="date" id="activate_date" class="form-control">
										</label>
										<br>
									</div>
								</div>

								<div class="form-row">
									<div class="form-holder form-holder-2">
										<label class="form-row-inner">
											<h4>Weblink Valid Till</h4>
											<input type="date" id="valid_till" class="form-control">
										</label>
									</div>
								</div>

								<div class="form-row">
									<div class="form-holder">
										<label class="form-row-inner">
											Reminder SMS
											<select id="reminder_sms" class="form-control" required>
												<option value="1">none</option>
												<option value="2">in every day</option>
												<option value="3">in alternate day</option>
												<option value="4">in after 03 days</option>
											</select>
										</label>
									</div>
								</div>

								<div class="form-row">
									<div class="form-holder">
										<label class="form-row-inner">
											Reminder Email
											<select id="reminder_email" class="form-control" required>
												<option value="1">none</option>
												<option value="2">in every day</option>
												<option value="3">in alternate day</option>
												<option value="4">in after 03 days</option>
											</select>
										</label>
									</div>
								</div>

								<!-- <button id="submitButton" type="button" class="btn btn-primary">Submit</button> -->

							</div>
						</section>


						<!-- SECTION 3 -->

						<h2>

							<span class="step-icon"><i class="zmdi zmdi-receipt"></i></span>

							<span class="step-text">Pricing</span>

						</h2>

						<section>

							<div class="inner">
								<div id="table1" class="table-container" style="display: none;">
									<h3>Verification Amount (Agency Wallet)</h3>
									<p>Verification amount (Included GST)</p>
									<div class="form-row">
										<div class="form-holder">
											<label class="form-row-inner">
												<table class="table table-bordered">
													<thead>
														<tr>
															<th>Verification</th>
															<!-- <th>Enter Your Amount</th> -->
															<th>Reserve Rate Rs.</th>
															<th>Total (Rs.)</th>
														</tr>
													</thead>
													<tbody>
														<tr></tr>
													</tbody>
												</table>
											</label>
										</div>
									</div>
								</div>

								<div id="table2" class="table-container" style="display: none;">
									<h3>Verification Amount (End User)</h3>
									<p>Enter Your addition Amount which will be added in every verification (Included GST)</p>

									<div class="form-row">
										<div class="form-holder">
											<label class="form-row-inner">
												<table class="table table-bordered">
													<thead>
														<tr>
															<th>Verification</th>
															<th>Enter Your Amount</th>
															<th>Reserve Rate Rs.</th>
															<th>Total (Rs.)</th>
														</tr>
													</thead>
													<tbody>
														<tr></tr>
													</tbody>
												</table>
											</label>
										</div>
									</div>
								</div>
							</div>
						</section>
						<h2>

							<span class="step-icon"><i class="zmdi zmdi-check"></i></span>

							<span class="step-text">Publish</span>

						</h2>

						<section>

							<div class="inner">


								<div class="form-row">
									<div class="form-holder" style="width: 100%;">
										<label class="form-row-inner">

										</label>
									</div>

								</div>


							</div>



					</div>

					</section>

					<!-- SECTION 4 -->





			</div>

			</form>

		</div>

	</div>

	</div>
	<script src="js/jquery-3.3.1.min.js"></script>

	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script>
		// Show/hide tables based on radio button selection
		function toggleGridVisibility() {

			$('#table1').css('display', '');
			$('#table2').css('display', 'none');
			// $('#aw_row').css('display', 'none');

		}



		function toggleGridVisibility1() {
			$('#table1').css('display', 'none');
			$('#table2').css('display', '');

		}





		function updateFileName() {
			const fileInput = document.getElementById('upload-excel');
			const fileName = fileInput.files[0].name;
			const fileNameInput = document.getElementById('file-name');
			const file_name_01 = document.getElementById('file_name_01');
			const uploadForm = document.getElementById('uploadForm');

			fileNameInput.value = fileName;
			file_name_01.value = fileName;
			uploadForm.style.display = 'block';
		}
		$(document).ready(function() {
	$('#requestForm').on('change', '#request-no', function() {
		let container = document.getElementById('card_01');
		let tbody1 = document.querySelector('#table1 tbody');
		let tbody2 = document.querySelector('#table2 tbody');

		// Clear previous content
		container.innerHTML = '';
		tbody1.innerHTML = '';
		tbody2.innerHTML = '';

		var requestNo = $(this).val();
		var agencyId = $('#agency_id').val();
		var bulkId = $('#bulk_id').val();

		$.ajax({
			url: 'crtlcontroller.php', // Change to your PHP file path
			type: 'POST',
			data: {
				request_no: requestNo,
				agency_id: agencyId,
				bulk_id: bulkId
			},
			dataType: 'json',
			success: function(response) {
				if (response.error) {
					alert(response.error);
				} else {
					console.log(response);
					$('#verification-for').text(response.generated_for);

					var verifications = response.verifications.split(',');
					var arrVeri = <?php echo $jsonArrVeri; ?>;
					console.log(arrVeri);
					let aadharTotalAmount = 0;
					let panTotalAmount = 0;
					let voterTotalAmount = 0;

					// Store rates and amounts
					let rateMap = {};

					arrVeri.forEach(function(element) {
						rateMap[element.verification_id] = {
							rate: parseFloat(element.rate),
							cgstPercentage: parseFloat(element.cgst_percentage),
							sgstPercentage: parseFloat(element.sgst_percentage),
							reserveRate: parseFloat(element.reserve_rate) // Assuming reserve_rate exists
						};

						let cgstAmount = (rateMap[element.verification_id].rate * rateMap[element.verification_id].cgstPercentage) / 100;
						let sgstAmount = (rateMap[element.verification_id].rate * rateMap[element.verification_id].sgstPercentage) / 100;

						rateMap[element.verification_id].totalAmount = rateMap[element.verification_id].rate + cgstAmount + sgstAmount;
					});

					console.log(rateMap);

					verifications.forEach(function(element) {
						let verificationType = '';
						let reserveRate = '';
						let totalAmount = '';

						switch (element) {
							case '1':
								verificationType = 'Adhaar';
								reserveRate = rateMap["DVF-00001"].reserveRate.toFixed(2);
								totalAmount = rateMap["DVF-00001"].totalAmount.toFixed(2);
								break;
							case '2':
								verificationType = 'Pan Card';
								reserveRate = rateMap["DVF-00002"].reserveRate.toFixed(2);
								totalAmount = rateMap["DVF-00002"].totalAmount.toFixed(2);
								break;
							case '3':
								verificationType = 'Voter';
								reserveRate = rateMap["DVF-00003"].reserveRate.toFixed(2);
								totalAmount = rateMap["DVF-00003"].totalAmount.toFixed(2);
								break;
							case '4':
								verificationType = 'DL';
								reserveRate = rateMap["DVF-00004"].reserveRate.toFixed(2);
								totalAmount = rateMap["DVF-00004"].totalAmount.toFixed(2);
								break;
							case '5':
								verificationType = 'E-Crime';
								reserveRate = rateMap["DVF-00005"].reserveRate.toFixed(2);
								totalAmount = rateMap["DVF-00005"].totalAmount.toFixed(2);
								break;
							default:
								verificationType = 'Unknown';
						}

						// Add to card_01
						let divElement = document.createElement('div');
						divElement.className = 'col-md-4';
						divElement.textContent = verificationType;
						container.appendChild(divElement);

						// Add to table1
						let newRow1 = document.createElement('tr');
						newRow1.innerHTML = `
                            <td>${verificationType}</td>
                            <td>${reserveRate}</td>
                            <td class="total-amount">${totalAmount}</td>
                        `;
						tbody1.appendChild(newRow1);

						// Add to table2
						let newRow2 = document.createElement('tr');
						newRow2.innerHTML = `
                            <td>${verificationType}</td>
                            <td><input type="text" name="amount_${element}" class="input-amount" data-reserve-rate="${reserveRate}" /></td>
                            <td>${reserveRate}</td>
                            <td class="total-amount">${totalAmount}</td>
                        `;
						tbody2.appendChild(newRow2);
					});

					// Event listener for input changes
					$('.input-amount').on('input', function() {
						let enteredAmount = parseFloat($(this).val());
						let reserveRate = parseFloat($(this).data('reserve-rate'));
						let totalAmount = enteredAmount * reserveRate;
						$(this).closest('tr').find('.total-amount').text(totalAmount.toFixed(2));
					});

					// Show the tables
					$('#table1').show();
					$('#table2').show();
				}
			},
			error: function(xhr, status, error) {
				console.error(xhr.responseText);
			}
		});
	});
});



		$(document).ready(function() {

			$('#uploadForm').on('submit', function(e) {

				e.preventDefault();



				var formData = new FormData(this);



				$.ajax({

					url: 'https://mounarchtech.com/vocoxp/ctrlcode.php',

					type: 'POST',

					data: formData,

					contentType: false,

					processData: false,

					success: function(response) {

						console.log(response); // Log response for debugging

						var result = JSON.parse(response);

						var responseDiv = $('#response');

						responseDiv.removeClass('alert-success alert-danger');

						if (result.status === 'success') {

							responseDiv.addClass('alert-success');

							responseDiv.html(result.message);

						} else {

							responseDiv.addClass('alert-danger');

							responseDiv.html(result.message);

						}

						responseDiv.show();

					},

					error: function(jqXHR, textStatus, errorThrown) {

						console.error('AJAX error:', textStatus, errorThrown); // Log error for debugging

						var responseDiv = $('#response');

						responseDiv.removeClass('alert-success');

						responseDiv.addClass('alert-danger');

						responseDiv.html('Error: ' + textStatus + ' - ' + errorThrown);

						responseDiv.show();

					}

				});



			});

		});

		// Ensure jQuery is included in your project
		$(document).ready(function() {
			// Add an event listener for the Finish button click
			$(document).on('click', '#final_submit a[href$="#finish"]', function(e) {
				e.preventDefault(); // Prevent the default form submission

				// Create a FormData object to handle file uploads and other form data
				var formData = new FormData();

				// Append file input value if a file is selected
				var fileInput = $('#upload-excel')[0].files[0];
				if (fileInput) {
					formData.append('file_name', fileInput);
				}

				// Append other form fields
				formData.append('agency_id', $('#agency_id').val());
				formData.append('bulk_id', $('#bulk_id').val());
				formData.append('amount', $('input[name="amount"]:checked').val());
				formData.append('activate_date', $('#activate_date').val());
				formData.append('valid_till', $('#valid_till').val());
				formData.append('reminder_sms', $('#reminder_sms').val());
				formData.append('reminder_email', $('#reminder_email').val());

				// Append table data
				formData.append('table1', JSON.stringify(collectTableData('#table1')));
				formData.append('table2', JSON.stringify(collectTableData('#table2')));

				// Send the form data using AJAX
				$.ajax({
					url: 'ctrlallinsert.php', // Replace with your server endpoint
					type: 'POST',
					data: formData,
					processData: false, // Prevent jQuery from automatically transforming the data into a query string
					contentType: false, // Prevent jQuery from setting the Content-Type header
					success: function(response) {
						// Handle the response from the server
						console.log(response);
						// Optionally, you could display a success message to the user
						alert('Form submitted successfully!');
					},
					error: function(xhr, status, error) {
						// Handle any errors
						console.error('Error:', error);
						alert('An error occurred while submitting the form.');
					}
				});
			});

			// Function to collect table data
			function collectTableData(tableId) {
				var data = [];
				$(tableId + ' tbody tr').each(function() {
					var row = {};
					$(this).find('td').each(function(index) {
						row['column' + index] = $(this).text(); // Collect data from each cell
					});
					if (!$.isEmptyObject(row)) {
						data.push(row);
					}
				});
				return data;
			}
		});


		document.getElementById('submitOtp').addEventListener('click', function(event) {
			event.preventDefault();

			let mobileNo = "<?php echo $arr_agency['mobile_no']; ?>";

			var xhr = new XMLHttpRequest();
			xhr.open('POST', 'ctrlsendotp.php', true);
			xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

			xhr.onreadystatechange = function() {
				if (xhr.readyState === XMLHttpRequest.DONE) {
					if (xhr.status === 200) {
						let serverOtp = xhr.responseText.trim(); // OTP from the server

						console.log('OTP Sent: ', serverOtp);

						// Show OTP input field and verify button
						document.getElementById('userOtp').style.display = 'block';
						document.getElementById('verifyOtp').style.display = 'block';

						// Store the OTP in a variable for later verification
						window.receivedOtp = serverOtp;
					} else {
						console.error('An error occurred:', xhr.statusText);
					}
				}
			};

			xhr.send('mobile_no=' + encodeURIComponent(mobileNo) + '&action=send_otp');
		});

		// Event listener for the Verify OTP button
		document.getElementById('verifyOtp').addEventListener('click', function(event) {
			event.preventDefault();

			let userOtp = document.getElementById('userOtp').value.trim();

			if (userOtp === window.receivedOtp) {
				alert('OTP verified successfully!');
				document.querySelector('.otp-card').style.display = 'none';

				// Show the content block
				document.querySelector('.page-content').style.display = '';
				// Proceed with next steps
			} else {
				alert('Incorrect OTP. Please try again.');
			}
		});
	</script>









	<script src="js/jquery.steps.js"></script>

	<script src="js/jquery-ui.min.js"></script>

	<script src="js/main.js"></script>

</body>

</html>