<x-layout>

    <div class="col-md-12">
        <div class="container">
            <h2>Edit Customer</h2>

            <form action="{{ route('customer.update', $customer->id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                @if($customer->photo)
                    <img id="imagePreview" src="{{ asset('photos/' . $customer->photo) }}" class="img-thumbnail mt-2 mb-4" width="100" alt="Customer Photo">
                @endif

                <div class="row">
                <div class="col-md-12">
                        <div class="photo_container">
                           <img id="imagePreview" src="{{ asset('images/user.png') }}" class="img-fluid" alt="USer Image">
                        </div>
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
                <div class="btn-content mt-5">
                    <button type="submit" class="btn btn-success">Update</button>
                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancel</a>
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
                "3 Month Cardio": 3000
            };

            const advanceAmounts = {
                "Normal": 500,
                "Cardio": 500,
                "3 Month Normal": 0,
                "3 Month Cardio": 0
            };

            packageSelect.addEventListener('change', function () {
                const selectedPackage = packageSelect.value;
                amountInput.value = packageAmounts[selectedPackage] || '';
                advanceInput.value = advanceAmounts[selectedPackage] || '';
            });
        });
    </script>
    
</x-layout>
