 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css"
        integrity="sha512-9xKTRVabjVeZmc+GUW8GgSmcREDunMM+Dt/GrzchfN8tkwHizc5RP4Ok/MXFFy5rIjJjzhndFScTceq5e6GvVQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"/>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

    <!-- Chart js CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" ></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- jQuery and DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <!-- Cropper.js CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">

    <!-- Cropper.js JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
    
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <title>Gym Admin Panel</title>

    <style>
        .spinner-border {
            width: 3rem;
            height: 3rem;
        }

        .model_close_btn{
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
        }
        
        .model_close_btn .close{
            border: none;
            background: none;
            font-size: 40px;
        }
    </style>

</head>
<body>

    <!-- Main content -->
    <div class="main-content2">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <h2 class="mt-3 mb-3">Safe Gym Customer Register</h2>
                                @if(session('error'))
                                    <div class="alert alert-danger">
                                        {{ session('error') }}
                                    </div>
                                @endif
                                
                                @if(session('success'))
                                    <div class="alert alert-success">
                                        {{ session('success') }}
                                    </div>
                                @endif


                                <div id="loader" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(255, 255, 255, 0.8); z-index: 9999; text-align: center;">
                                    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                                        <div class="spinner-border text-success" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <p class="mt-2">Processing your request, please wait...</p>
                                    </div>
                                </div>


                            <div class="add_user_container mb-5">
                                <form action="{{route('register')}}" method="post" enctype="multipart/form-data">
                                    @csrf
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="photo_container">
                                                  <img id="imagePreview" src="{{ asset('images/user.png') }}" class="img-fluid" alt="USer Image">
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form_group mt-2">
                                                            <label for="name" class="form-label">Name <span style="color: red; font-size: 20px">*</span></label>
                                                            <input type="text" id="name" name="name" class="form-control" placeholder="Enter your name" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form_group mt-2">
                                                            <label for="gender" class="form-label">Select Gender <span style="color: red; font-size: 20px">*</span></label>
                                                            <select id="gender" name="gender" class="form-select" required>
                                                                <option value="" disabled selected>Select Gender</option>
                                                                <option value="Male">Male</option>
                                                                <option value="Female">Female</option>
                                                                <option value="Special">Special</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form_group mt-2">
                                                            <label for="age" class="form-label">Age <span style="color: red; font-size: 20px">*</span></label>
                                                            <input type="text" id="age" name="age" class="form-control" placeholder="Enter your age" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form_group mt-2">
                                                            <label for="phone" class="form-label">Address <span style="color: red; font-size: 20px">*</span></label>
                                                            <textarea type="text" id="address" name="address" class="form-control" placeholder="Enter your address" required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form_group mt-2">
                                                            <label for="weight" class="form-label">Weight <span style="font-size: 20px"></span></label>
                                                            <input type="text" id="weight" name="weight" class="form-control" placeholder="Enter your weight">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form_group mt-2">
                                                            <label for="height" class="form-label">Height <span style="font-size: 20px"></span></label>
                                                            <input type="text" id="height" name="height" class="form-control" placeholder="Enter your height">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                    <div class="form_group mt-2">
                                                            <label for="blood_group" class="form-label">Select Blood Group <span style="color: red; font-size: 20px">*</span></label>
                                                            <select id="blood_group" name="blood_group" class="form-select" required>
                                                                <option value="" disabled selected>Select Blood Group</option>
                                                                <option value="A+ve">A+ve</option>
                                                                <option value="A-ve">A-ve</option>
                                                                <option value="B+ve">B+ve</option>
                                                                <option value="B-ve">B-ve</option>
                                                                <option value="O+ve">O+ve</option>
                                                                <option value="O-ve">O-ve</option>
                                                                <option value="AB+ve">AB+ve</option>
                                                                <option value="AB-ve">AB-ve</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                    <div class="form_group mt-2">
                                                            <label for="bp_sugar" class="form-label">BP</label>
                                                            <select id="bp_sugar" name="bp_sugar" class="form-select">
                                                                <option value="" disabled selected>Select Yes Or No</option>
                                                                <option value="Yes">Yes</option>
                                                                <option value="No">No</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                    <div class="form_group mt-2">
                                                            <label for="sugar" class="form-label">Sugar</label>
                                                            <select id="sugar" name="sugar" class="form-select">
                                                                <option value="" disabled selected>Select Yes Or No</option>
                                                                <option value="Yes">Yes</option>
                                                                <option value="No">No</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form_group mt-2">
                                                            <label for="problems" class="form-label">Other Problems</label>
                                                            <input type="text" id="other_problems" name="other_problems" class="form-control" placeholder="Enter your other problems">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form_group mt-2">
                                                            <label for="join_date" class="form-label">Join Date <span style="color: red; font-size: 20px">*</span></label>
                                                            <input type="date" id="date" name="date" class="form-control" placeholder="Enter Join Date" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form_group mt-2">
                                                            <label for="email" class="form-label">Email <span style="color: red; font-size: 20px">*</span></label>
                                                            <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email address" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form_group mt-2">
                                                            <label for="phone" class="form-label">Phone <span style="color: red; font-size: 20px">*</span></label>
                                                            <input type="tel" id="phone" name="phone" class="form-control" placeholder="Enter your phone number" maxlength="10" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form_group mt-2">
                                                            <label for="package" class="form-label">Package <span style="color: red; font-size: 20px">*</span></label>
                                                            <select id="package" name="package" class="form-select" required>
                                                                <option value="" disabled selected>Select a package</option>
                                                                <option value="Normal">Normal</option>
                                                                <option value="Cardio">Cardio</option>
                                                                <option value="3 Month Normal">3 Month Normal</option>
                                                                <option value="3 Month Cardio">3 Month Cardio</option>
                                                                <option value="12 Month Normal">1 Year Normal</option>
                                                                <option value="12 Month Cardio">1 Year Cardio</option>
                                                                <option value="1 Month Trainer">Personal Trainer</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form_group mt-2">
                                                            <label for="amount" class="form-label">Amount <span style="font-size: 20px"></span></label>
                                                            <input type="number" id="amount" name="amount" class="form-control" placeholder="Enter amount" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form_group mt-2" style="display: none;">
                                                            <label for="advance" class="form-label">Advance <span style="font-size: 20px"></span></label>
                                                            <input type="number" id="advance" name="advance" class="form-control" placeholder="Enter advance" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form_group mt-2">
                                                            <label for="photo" class="form-label">Upload Photo <span style="color: red; font-size: 20px">*</span></label>
                                                            <input type="file" id="photo" name="photo" class="form-control" accept="image/*" required>
                                                        </div>
                                                    </div>

                                                    <!-- Cropping Modal -->
                                                    <div id="cropModal" class="modal" tabindex="-1" role="dialog">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header model_close_btn">
                                                                    <h5 class="modal-title">Crop Image</h5>
                                                                    <button type="button" class="close" onclick="closeCropperModal()">×</button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div>
                                                                        <img id="cropImage" style="width: 100%;" alt="Crop Image">
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-primary" id="cropSaveBtn">Crop & Save</button>
                                                                    <button type="button" class="btn btn-secondary" onclick="closeCropperModal()">Close</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="text-left">
                                                            <button  type="submit" class="btn btn-success mt-5">Registration</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

    <script>

        document.addEventListener('DOMContentLoaded', function () {
            const packageSelect = document.getElementById('package');
            const amountInput = document.getElementById('amount');
            const advanceInput = document.getElementById('advance');

            const packageAmounts = {
                "Normal": 700,
                "Cardio": 1200,
                "3 Month Normal": 2000,
                "3 Month Cardio": 3000,
                "12 Month Normal": 6000,
                "12 Month Cardio": 10000,
                "1 Month Trainer": 6000,
            };

            const advanceAmounts = {
                "Normal": 300,
                "Cardio": 300,
                "3 Month Normal": 0,
                "3 Month Cardio": 0,
                "12 Month Normal": 0,
                "12 Month Cardio": 0,
                "1 Month Trainer": 0,
            };

            packageSelect.addEventListener('change', function () {
                const selectedPackage = packageSelect.value;
                amountInput.value = packageAmounts[selectedPackage] || '';
                advanceInput.value = advanceAmounts[selectedPackage] || '';
            });
        });
    </script>

    <!-- Cropping functions  start here -->
    <script>
        let cropper;
        let croppedCanvas;

        // When user selects an image
        document.getElementById('photo').addEventListener('change', function (event) {
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    document.getElementById('cropImage').src = e.target.result;
                    openCropperModal();
                };

                reader.readAsDataURL(file);
            }
        });

        function openCropperModal() {
            $('#cropModal').show(); // Show the modal
            const image = document.getElementById('cropImage');

            // Initialize Cropper.js
            cropper = new Cropper(image, {
                aspectRatio: 1, // Square crop
                viewMode: 2, // Restricts cropping to the image
                preview: '.photo_container img'
            });
        }

        function closeCropperModal() {
            $('#cropModal').hide(); // Hide the modal
            if (cropper) {
                cropper.destroy();
            }
        }

        document.getElementById('cropSaveBtn').addEventListener('click', cropImage);


        function cropImage() {
            if (cropper) {
                croppedCanvas = cropper.getCroppedCanvas({
                    width: 300, // Set the cropped image width
                    height: 300 // Set the cropped image height
                });

                // Show the cropped image preview
                document.getElementById('imagePreview').src = croppedCanvas.toDataURL();

                // Convert cropped image to Blob and append it to a hidden input field
                croppedCanvas.toBlob((blob) => {
                    const fileInput = document.getElementById('photo');
                    const file = new File([blob], "cropped-image.jpg", { type: "image/jpeg" });

                    // Create a new DataTransfer object and replace the file
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    fileInput.files = dataTransfer.files;
                });

                closeCropperModal();
            }
        }
    </script>
    <!-- Cropping functions  end here -->

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('open');
        }

        document.getElementById('photo').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('imagePreview').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>

    <script>
        document.querySelector('form').addEventListener('submit', function () {
            // Show the loader
            document.getElementById('loader').style.display = 'block';

            // Disable the submit button to prevent multiple submissions
            const submitButton = this.querySelector('button[type="submit"]');
            submitButton.disabled = true;
            submitButton.textContent = 'Processing...';
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- <script src="{{ asset('javascript/chart.js') }}"></script> -->

    <!-- Include jsPDF and html2canvas -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
</body>
</html>

