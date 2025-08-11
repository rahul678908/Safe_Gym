<x-layout>
    
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

    <div class="col-md-12">
        <div class="container">
            <h2>Edit Customer</h2>

            <form action="{{ route('customer.update', $customer->id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                @if($customer->photo)
                    <img id="imagePreview" src="{{ asset('photos/' . $customer->photo ) }}" class="img-thumbnail mt-2 mb-4" width="100" alt="Customer Photo">
                @endif

                <div class="row">
                <div class="col-md-12">
                        <!--<div class="photo_container">-->
                        <!--   <img id="imagePreview" src="{{ asset('images/user.png') }}" class="img-fluid" alt="USer Image">-->
                        <!--</div>-->
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form_group mt-2">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" id="name" name="name"  class="form-control" value="{{ $customer->name ?? '' }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form_group mt-2">
                                    <label for="gender" class="form-label">Select Gender</label>
                                    <select id="gender" name="gender" class="form-select" value="{{ $customer->gender }}" required>
                                        <option value="" disabled {{ empty($customer->gender) ? 'selected' : '' }} selected>Select Gender</option>
                                        <option value="Male" {{ $customer->gender == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ $customer->gender == 'Female' ? 'selected' : '' }}>Female</option>
                                        <option value="Special" {{ $customer->gender == 'Special' ? 'selected' : '' }}>Special</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form_group mt-2">
                                    <label for="age" class="form-label">Age</label>
                                    <input type="text" id="age" name="age" class="form-control" value="{{ $customer->age ?? '' }}" placeholder="Enter your age" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form_group mt-2">
                                    <label for="weight" class="form-label">Weight</label>
                                    <input type="text" id="weight" name="weight" class="form-control" value="{{ $customer->weight ?? '' }}" placeholder="Enter your weight">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form_group mt-2">
                                    <label for="height" class="form-label">Height</label>
                                    <input type="text" id="height" name="height" class="form-control" value="{{ $customer->height ?? '' }}" placeholder="Enter your height">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form_group mt-2">
                                    <label for="blood_group" class="form-label">Select Blood Group</label>
                                    <select id="blood_group" name="blood_group" class="form-select" required>
                                        <option value="" disabled {{ empty($customer->blood_group) ? 'selected' : '' }}>Select Blood Group</option>
                                        <option value="A+ve" {{ $customer->blood_group == 'A+ve' ? 'selected' : '' }}>A+ve</option>
                                        <option value="A-ve" {{ $customer->blood_group == 'A-ve' ? 'selected' : '' }}>A-ve</option>
                                        <option value="B+ve" {{ $customer->blood_group == 'B+ve' ? 'selected' : '' }}>B+ve</option>
                                        <option value="B-ve" {{ $customer->blood_group == 'B-ve' ? 'selected' : '' }}>B-ve</option>
                                        <option value="O+ve" {{ $customer->blood_group == 'O+ve' ? 'selected' : '' }}>O+ve</option>
                                        <option value="O-ve" {{ $customer->blood_group == 'O-ve' ? 'selected' : '' }}>O-ve</option>
                                        <option value="AB+ve" {{ $customer->blood_group == 'AB+ve' ? 'selected' : '' }}>AB+ve</option>
                                        <option value="AB-ve" {{ $customer->blood_group == 'AB-ve' ? 'selected' : '' }}>AB-ve</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                               <div class="form_group mt-2">
                                    <label for="bp_sugar" class="form-label">BP</label>
                                    <select id="bp_sugar" name="bp_sugar" class="form-select" value="{{ $customer->bp_sugar }}">
                                    <option value="" disabled {{ empty($customer->bp_sugar) ? 'selected' : '' }}>Select Yes Or No</option>
                                        <option value="Yes" {{ $customer->bp_sugar == 'Yes' ? 'selected' : '' }}>Yes</option>
                                        <option value="No" {{ $customer->bp_sugar == 'No' ? 'selected' : '' }}>No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                               <div class="form_group mt-2">
                                    <label for="sugar" class="form-label">Sugar</label>
                                    <select id="sugar" name="sugar" class="form-select">
                                        <option value="" disabled {{ empty($customer->sugar) ? 'selected' : '' }}>Select Yes Or No</option>
                                        <option value="Yes" {{ $customer->sugar == 'Yes' ? 'selected' : '' }}>Yes</option>
                                        <option value="No" {{ $customer->sugar == 'No' ? 'selected' : '' }}>No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form_group mt-2">
                                    <label for="problems" class="form-label">Other Problems</label>
                                    <input type="text" id="other_problems" name="other_problems" class="form-control" value="{{ $customer->other_problems ?? '' }}" placeholder="Enter your other problems">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form_group mt-2">
                                    <label for="join_date" class="form-label">Select Join Date</label>
                                    <input type="date" id="date" name="date" class="form-control" value="{{ $customer->date ?? '' }}" placeholder="Select a date">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form_group mt-2">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email address" value="{{ $customer->email ?? '' }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form_group mt-2">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="tel" id="phone" name="phone" class="form-control" placeholder="Enter your phone number" value="{{ $customer->phone ?? '' }}" maxlength="10" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form_group mt-2">
                                    <label for="phone" class="form-label">Address</label>
                                    <textarea type="text" id="address" name="address" class="form-control" placeholder="Enter your address" required>{{ $customer->address ?? '' }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form_group mt-2">
                                    <label for="package" class="form-label">Package</label>
                                    <select id="package" name="package" class="form-select" required>
                                        <option value="" disabled {{ empty($customer->package) ? 'selected' : '' }}>Select a package</option>
                                        <option value="Normal" {{ $customer->package == 'Normal' ? 'selected' : '' }}>Normal</option>
                                        <option value="Cardio" {{ $customer->package == 'Cardio' ? 'selected' : '' }}>Cardio</option>
                                        <option value="3 Month Normal" {{ $customer->package == '3 Month Normal' ? 'selected' : '' }}>3 Month Normal</option>
                                        <option value="3 Month Cardio" {{ $customer->package == '3 Month Cardio' ? 'selected' : '' }}>3 Month Cardio</option>
                                        <option value="12 Month Normal" {{ $customer->package == '12 Month Normal' ? 'selected' : '' }}>1 Year Normal</option>
                                        <option value="12 Month Cardio" {{ $customer->package == '12 Month Cardio' ? 'selected' : '' }}>1 Year Cardio</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form_group mt-2">
                                    <label for="amount" class="form-label">Amount</label>
                                    <input type="number" id="amount" name="amount" class="form-control" value="{{ $customer->amount ?? '' }}" placeholder="Enter amount" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form_group mt-2">
                                    <label for="advance" class="form-label">Advance</label>
                                    <input type="number" id="advance" name="advance" class="form-control" value="{{ $customer->advance ?? '' }}" placeholder="Enter advance" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form_group mt-2">
                                    <label for="photo" class="form-label">Upload Photo</label>
                                    <input type="file" id="photo" name="photo" class="form-control" accept="image/*">
                                </div>
                            </div>
                        </div>
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
                
                <div class="btn-content mt-5">
                    <button type="submit" class="btn btn-success">Update</button>
                    <a href="{{ route('add_user') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script>
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
        document.addEventListener('DOMContentLoaded', function () {
            const packageSelect = document.getElementById('package');
            const amountInput = document.getElementById('amount');
            const advanceInput = document.getElementById('advance');

            const packageAmounts = {
                "Normal": 700,
                "Cardio": 1000,
                "3 Month Normal": 2000,
                "3 Month Cardio": 3000,
                "12 Month Normal": 6000,
                "12 Month Cardio": 10000
            };

            const advanceAmounts = {
                "Normal": 300,
                "Cardio": 500,
                "3 Month Normal": 0,
                "3 Month Cardio": 0,
                "12 Month Normal": 0,
                "12 Month Cardio": 0
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
    
</x-layout>
