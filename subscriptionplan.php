<!-- Teeny Icon -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/teenyicons@latest/dist/teenyicons.min.css">

<style>
    .card {
        border-radius: 50px !important;
        border: none !important;
        box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
    }

    .card:hover {
        transition: .5s;
        box-shadow: 0px 0px 30px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        border-radius: 50px 50px 0 0 !important;
        background-color: #eaa451;
        display: flex;
        justify-content: center;
        height: 100px;
    }

    .card-foote1 {
        height: 70px;
        border-radius: 0px 0px 40px 40px !important;
        background-color: blue;
    }

    .card-foote2 {
        height: 70px;
        border-radius: 0px 0px 40px 40px !important;
        background-color: blue;
    }

    .card-foote3 {
        height: 70px;
        border-radius: 0px 0px 40px 40px !important;
        background-color: blue;
    }

    .card__list-icon {
        color: #eaa451;
    }

    .select {
        border-top: 1px solid rgba(0, 0, 0, 0.5);
        border-bottom: 1px solid rgba(0, 0, 0, 0.5);
    }

    .f-select {
        border: none;
        color: green;
        font-weight: 500;
    }

    .pa {
        top: -18px;
    }

    .custom-badge1 {
        background-color: #C5FFBC;
        color: #128400;
        padding-top: 3px;
        /* Adjust padding as needed */
        padding-bottom: 3px;
        padding-left: 8px;
        padding-right: 8px;
        border-radius: 5px;
        /* Adjust border-radius as needed */
    }

    .custom-badge2 {
        background-color: #BF3E00;
        color: #FFC028;
        padding-top: 3px;
        /* Adjust padding as needed */
        padding-bottom: 3px;
        padding-left: 8px;
        padding-right: 8px;
        /* Adjust padding as needed */
        border-radius: 5px;
        /* Adjust border-radius as needed */
    }

    .custom-badge3 {
        background-color: #0E9AFF;
        color: #BBE2FF;
        padding-top: 3px;
        /* Adjust padding as needed */
        padding-bottom: 3px;
        padding-left: 8px;
        padding-right: 8px;
        border-radius: 5px;
        /* Adjust border-radius as needed */
    }

    .custom-badge-btn {
        background-color: #fff;
        color: #000;
        padding: 2 2 20 20;
        height: 2.5rem;
        align-items: center;
        justify-content: center;
        position: relative;
        margin-bottom: 20px;
        border-radius: 60px;
        border: none;
        cursor: pointer;
        /* Adjust border-radius as needed */
    }

    .bg-warning-white {
        background-color: #ffffff;
    }

    .text-white1 {
        font-weight: bold;
        color: black;
        /* Or any desired color */
    }

    .rounded-box {
        border-radius: 60px;
        box-shadow: none;
        background: #fff;
        color: #000;
    }

    .badge-container {
            display: flex;
            align-items: center;
        }

        .badge-3 {
            font-family: Poppins;
            font-weight: 800;
            font-size: 1.5rem;
            /* Adjust font size as needed */
            color: #fff;
            background: #4ECB71;
            padding: 10px 15px;
            /* Adjust padding for better text fitting */
            border-radius: 10px;
            text-align: center;
            line-height: 1;
            /* Adjust line-height to match font size */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            position: relative;
            overflow: hidden;
            white-space: nowrap;
            /* Prevent text wrapping */

        }

        .badge-3:before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 300%;
            height: 300%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.4), transparent);
            animation: shine 2s infinite;
            transform: rotate(-30deg);
        }

        @keyframes shine {
            0% {
                left: -100%;
            }

            100% {
                left: 100%;
            }
        }
        .border-gradient-rounded {
            
            border-radius: 60px;
            border: 3px solid transparent;
  /* Border */
  
  border-radius: 60px;
  background: 
    linear-gradient(to right, white, white), 
    linear-gradient( #D6D6D6, #D6D6D6); 
  background-clip: padding-box, border-box;
  background-origin: padding-box, border-box;
  
  /* Other styles */
    width: 1280px;
    height: 875px;
    padding: 12px;
}





</style>
<section class="container my-5">
    <div class="section-head text-center" style="margin-bottom: 100px;">
        <h2 class="title">Plans & Pricing</h2>
        <p>A Comprehensive Preparation Management to Excel in government exam by covering syllabus and focusing on
            enhancing knowledge, Skills & Strategies for Success.</p>
    </div>

    <div class="row g-4">
        <div class="badge-container justify-content-center align-content-center" style="margin-top:-20px;">
            <div class="border-gradient-rounded position-absolute" style="margin-top: 875px;"></div>        
            <div class="badge-3" style="font-family: Outfit;">Premium</div>            
        </div>

        <div class="col-md-4">

            <div class="card h-100">
                
                <div class="card-header" style="background-color:#191919;">
                    
                    <img src="images/background/Fresh.svg" alt="" height="150px" width="200px">
                </div>
                <div class="card-body">

                    <div class="text-center">
                        <span class="custom-badge1" style="font-size:14.75px; font-family:Outfit;">Best for Freshers</span>
                    </div>

                    <ul class="list-group mt-2">

                        <li class="d-flex align-items-baseline" style="margin-top:10px;">
                            <i class="fas fa-circle-check text-success me-2"></i>
                            <p class="card__list-description" style="font-family:Outfit;">Career Counselling</p>
                        </li>
                        <li class="d-flex align-items-baseline">
                            <i class="fas fa-circle-check text-success me-2"></i>
                            <ul class="card__list-description" style="font-family:Outfit;">
                                <li>Bundle Kit</li>
                                <li class="card__list-subtitle" style="font-family:Outfit;">• Standard Books</li>
                                <li class="card__list-subtitle" style="font-family:Outfit;">• Stationary</li>
                                <li class="card__list-subtitle" style="font-family:Outfit;">• Sheets</li>
                            </ul>
                        </li>

                        <li class="d-flex align-items-baseline" style="margin-top:10px;">
                            <i class="fas fa-circle-check text-success me-2"></i>
                            <p class="card__list-description" style="font-family:Outfit;">Personalised Support</p>
                        </li>
                        <li class="d-flex align-items-baseline">
                            <i class="fas fa-circle-check text-success me-2"></i>
                            <p class="card__list-description" style="font-family:Outfit;">Weekly Q/P Subscriptions</p>
                        </li>
                        <li class="d-flex align-items-baseline">
                            <i class="fas fa-circle-check text-success me-2"></i>
                            <p class="card__list-description" style="font-family:Outfit;">Test Packs</p>
                        </li>
                        <li class="d-flex align-items-baseline">
                            <i class="fas fa-circle-check text-success me-2"></i>
                            <p class="card__list-description" style="font-family:Outfit;">Weekly Doubt Clarification</p>
                        </li>
                        <li class="d-flex align-items-baseline">
                            <i class="fas fa-circle-check text-success me-2"></i>
                            <p class="card__list-description" style="font-family:Outfit;">Joblication</p>
                        </li>
                        <li class="d-flex align-items-baseline">
                            <i class="fas fa-circle-check text-success me-2"></i>
                            <p class="card__list-description" style="font-family:Outfit;">Membership - Club Activity</p>
                        </li>

                    </ul>

                </div>
                <div class="select py-1 d-flex justify-content-center align-items-center">
                    <select name="exam" id="examSelect" class="f-select">
                        <option value="default">Select Exam</option>
                        <option value="UPSC">UPSC</option>
                        <option value="TNPSC - Group 1">TNPSC Group 1</option>
                        <option value="TNPSC - Group 2">TNPSC Group 2</option>
                        <option value="TNPSC - Group 4">TNPSC Group 4</option>
                        <option value="CGL">SSC CGL</option>
                        <option value="CHSL">SSC CHSL</option>
                        <option value="MTS">SSC MTS</option>
                        <option value="Banking">Banking</option>
                    </select>
                </div>

                <div class="card-foote1 mt-5 pt-3 d-flex flex-wrap align-items-center justify-content-center position-relative" style="background: linear-gradient(to bottom, #1FDD00 0%, #1DB006 100%);">
                    <button id="planButton" class="custom-badge-btn align-items-center"><span class="text-center h6 p-3 text-white1">Choose Plan</span></button>
                    <p id="originalPrice" class="text-light text-decoration-line-through" style="font-weight:normal; font-size:18px; margin-right: 30px;"></p>
                </div>


                <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const planButton = document.getElementById('planButton');
        const plusPlanButton = document.getElementById('plusPlanBtn');
        const planSelect = document.getElementById('plan');
        const examSelect = document.getElementById('examSelect');
        const originalPrice = document.getElementById('originalPrice');
        
        // Pricing and months for each exam
        const pricing = {
            'UPSC': { price: '₹ 78,999', months: '10 months', originalPrice: '₹ 89,999' },
            'TNPSC - Group 1': { price: '₹ 64,999', months: '8 months', originalPrice: '₹ 69,999' },
            'TNPSC - Group 2': { price: '₹ 49,999', months: '6 months', originalPrice: '₹ 55,999' },
            'TNPSC - Group 4': { price: '₹ 41,999', months: '6 months', originalPrice: '₹ 49,999' },
            'CGL': { price: '₹ 49,999', months: '8 months', originalPrice: '₹ 55,999' },
            'CHSL': { price: '₹ 41,999', months: '6 months', originalPrice: '₹ 49,999' },
            'MTS': { price: '₹ 39,999', months: '6 months', originalPrice: '₹ 41,999' },
            'Banking': { price: '₹ 54,999', months: '8 months', originalPrice: '₹ 59,999' }
        };

        function updatePlanDetails() {
            const examValue = examSelect.value;

            if (examValue === 'default' || !pricing[examValue]) {
                planButton.innerHTML = '<span class="text-center h6 p-3 text-white1" style="font-family: outfit;">Choose Plan</span>';
                planButton.style.color = '';
                planButton.style.fontWeight = '';
                originalPrice.style.display = 'none';
                planSelect.innerHTML = '<option value="">Choose Plan</option>';
                return;
            }

            const { price, months, originalPrice: origPrice } = pricing[examValue];
            const planValue = `${price} / ${months}`;
            const planOption = 'Fresh';

            planButton.innerHTML = `<span class="text-center h6 p-3 text-white1" style="font-weight: bold; font-family: outfit;">${planValue}</span>`;
            planButton.style.color = '#000000';
            planButton.style.fontWeight = 'bold';
            originalPrice.innerHTML = `<span style="font-weight: bold; font-family: outfit; margin-left:10px;">${origPrice}</span>`;
            originalPrice.style.display = 'block';
            originalPrice.style.color = '#ff0000';
            originalPrice.style.fontWeight = 'bold';

            planSelect.innerHTML = `<option value="GovPrep - ${planOption} - ${examValue} - ${planValue}">GovPrep - ${planOption} - ${examValue} - ${planValue}</option>`;
            planSelect.value = `GovPrep - ${planOption} - ${examValue} - ${planValue}`;
        }

        function handlePlanButtonClick() {
            const examValue = examSelect.value;

            if (examValue === 'default' || !pricing[examValue]) {
                alert('Please select an exam');
                return;
            }

            const { price, months, originalPrice: origPrice } = pricing[examValue];
            const planValue = `${price} / ${months}`;
            const planOption = 'Fresh';

            if (planButton.classList.contains('selected')) {
                // Uncheck: remove icon and deselect plan
                planButton.innerHTML = `<span class="text-center h6 p-3 text-white1" style="font-weight: bold; font-family: outfit; padding: 0 20px;">${planValue}</span>`;
                planButton.classList.remove('selected');
                planButton.style.color = '';
                planButton.style.fontWeight = '';
                originalPrice.innerHTML = `<span style="font-weight: bold; font-family: outfit; margin-left:10px;">${origPrice}</span>`;
                originalPrice.style.color = '';
                originalPrice.style.fontWeight = '';
                planSelect.innerHTML = '<option value="">Choose Plan</option>';
                
                // Deselect Plus plan button if selected
                if (plusPlanButton.classList.contains('selected')) {
                    plusPlanButton.click();
                }
            } else {
                // Check: add icon and select plan
                planButton.innerHTML = `<i class="fas fa-check-circle" style="margin-left:10px;"></i> <span class="text-center h6 p-3 text-white1" style="font-weight: bold; font-family: outfit; margin-right:10px; padding: 0 10px;">${planValue}</span>`;
                planButton.classList.add('selected');
                planButton.style.color = '#000000';
                planButton.style.fontWeight = 'bold';
                originalPrice.innerHTML = `<span style="font-weight: bold; font-family: outfit; margin-left:10px;">${origPrice}</span>`;
                originalPrice.style.color = '#ff0000';
                originalPrice.style.fontWeight = 'bold';
                planSelect.innerHTML = `<option value="GovPrep - ${planOption} - ${examValue} - ${planValue}">GovPrep - ${planOption} - ${examValue} - ${planValue}</option>`;
                planSelect.value = `GovPrep - ${planOption} - ${examValue} - ${planValue}`;
                
                // Deselect Plus plan button if selected
                if (plusPlanButton.classList.contains('selected')) {
                    plusPlanButton.click();
                }
            }

            localStorage.setItem('selectedExam', examValue);
            localStorage.setItem('buttonState', planButton.classList.contains('selected') ? 'selected' : 'unselected');
        }

        examSelect.addEventListener('change', function() {
            updatePlanDetails();
            if (examSelect.value !== 'default') {
                planButton.classList.add('pulse');
                setTimeout(() => planButton.classList.remove('pulse'), 1000);
            }
        });

        planButton.addEventListener('click', handlePlanButtonClick);
    });
</script>

            </div>

        </div>

        <div class="col-md-4">

            <div class="card h-100">
                <div class="card-header" style="background-color:#191919;">
                    <img src="images/background/plus.svg" alt="" height="190px" width="200px">
                </div>
                <div class="card-body">

                    <div class="text-center">
                        <span class="custom-badge2" style="font-size:14.75px; font-family:Outfit;">Best for Experienced 2Yrs+</span>
                    </div>

                    <ul class="list-group mt-2">

                        <li class="d-flex align-items-baseline" style="margin-top:10px;">
                            <i class="fas fa-circle-check text-success me-2"></i>
                            <p class="card__list-description" style="font-family:Outfit;">Career Counselling</p>
                        </li>

                        <li class="d-flex align-items-baseline">
                            <i class="fas fa-circle-check text-success me-2"></i>
                            <p class="card__list-description" style="font-family:Outfit;">Personalised Support</p>
                        </li>
                        <li class="d-flex align-items-baseline">
                            <i class="fas fa-circle-check text-success me-2"></i>
                            <p class="card__list-description" style="font-family:Outfit;">Weekly Q/P Subscriptions</p>
                        </li>
                        <li class="d-flex align-items-baseline">
                            <i class="fas fa-circle-check text-success me-2"></i>
                            <p class="card__list-description" style="font-family:Outfit;">Test Packs</p>
                        </li>
                        <li class="d-flex align-items-baseline">
                            <i class="fas fa-circle-check text-success me-2"></i>
                            <p class="card__list-description" style="font-family:Outfit;">Weekly Doubt Clarification</p>
                        </li>
                        <li class="d-flex align-items-baseline">
                            <i class="fas fa-circle-check text-success me-2"></i>
                            <p class="card__list-description" style="font-family:Outfit;">Joblication</p>
                        </li>
                        <li class="d-flex align-items-baseline">
                            <i class="fas fa-circle-check text-success me-2"></i>
                            <p class="card__list-description" style="font-family:Outfit;">Membership - Club Activity</p>
                        </li>

                    </ul>

                </div>
                <div class="select py-1 d-flex justify-content-center align-items-center">

                    <select name="plusExam" id="plusSelect" class="f-select">
                    <option value="default">Select Exam</option>
                        <option value="UPSC">UPSC</option>
                        <option value="TNPSC - Group 1">TNPSC Group 1</option>
                        <option value="TNPSC - Group 2">TNPSC Group 2</option>
                        <option value="TNPSC - Group 4">TNPSC Group 4</option>
                        <option value="CGL">SSC CGL</option>
                        <option value="CHSL">SSC CHSL</option>
                        <option value="MTS">SSC MTS</option>
                        <option value="BANKING">Banking</option>
                    </select>

                </div>

                <div class="card-foote2 mt-5 pt-3 d-flex flex-wrap align-items-center justify-content-center position-relative" style="background: linear-gradient(to bottom, #E200A7 0%, #BB008A 100%);">
                    <button id="plusPlanBtn" class="custom-badge-btn align-items-center "><span class="text-center h6 p-3 text-white1">Choose Plan</span></button>
                    <p id="plusOriginalPrice" class="text-light text-decoration-line-through" style="font-weight:normal; font-size:18px; margin-right: 30px;"></p>
                </div>
                <script>
    document.addEventListener('DOMContentLoaded', function() {
        const plusPlanButton = document.getElementById('plusPlanBtn');
        const plusPlanSelect = document.getElementById('plan');
        const plusExamSelect = document.getElementById('plusSelect');
        const plusOriginalPrice = document.getElementById('plusOriginalPrice');
        const plusPlanForm = document.getElementById('plusPlanForm');
        const selectedExamInput = document.getElementById('selectedExamInput');
        const selectedPlanInput = document.getElementById('selectedPlanInput');

        // Pricing and months for each exam
        const pricingDetails = {
            'UPSC': { price: '₹ 74,999', months: '10 months', plusOriginalPrice: '₹ 84,999' },
            'TNPSC - Group 1': { price: '₹ 59,999', months: '8 months', plusOriginalPrice: '₹ 63,999' },
            'TNPSC - Group 2': { price: '₹ 45,999', months: '6 months', plusOriginalPrice: '₹ 50,999' },
            'TNPSC - Group 4': { price: '₹ 37,999', months: '6 months', plusOriginalPrice: '₹ 44,999' },
            'CGL': { price: '₹ 45,999', months: '8 months', plusOriginalPrice: '₹ 51,999' },
            'CHSL': { price: '₹ 37,999', months: '6 months', plusOriginalPrice: '₹ 44,999' },
            'MTS': { price: '₹ 34,999', months: '6 months', plusOriginalPrice: '₹ 36,999' },
            'BANKING': { price: '₹ 49,999', months: '8 months', plusOriginalPrice: '₹ 53,999' }
        };

        function updatePlusPlanDetails() {
            const selectedExam = plusExamSelect.value;

            if (selectedExam === 'default' || !pricingDetails[selectedExam]) {
                plusPlanButton.innerHTML = '<span class="text-center h6 p-3 text-white1" style="font-family: outfit;">Choose Plan</span>';
                plusPlanButton.style.color = '';
                plusPlanButton.style.fontWeight = '';
                plusOriginalPrice.style.display = 'none';
                plusPlanSelect.innerHTML = '<option value="">Choose Plan</option>';
                return;
            }

            const { price, months, plusOriginalPrice: orgprice } = pricingDetails[selectedExam];
            const planDescription = `${price} / ${months}`;
            const plusPlanOption = 'Plus';

            plusPlanButton.innerHTML = `<span class="text-center h6 p-3 text-white1" style="font-family: outfit;">${planDescription}</span>`;
            plusPlanButton.style.color = '#000000';
            plusPlanButton.style.fontWeight = 'bold';
            plusOriginalPrice.innerHTML = `<span style="font-weight: bold; font-family: outfit; margin-left:10px;">${orgprice}</span>`;
            plusOriginalPrice.style.display = 'block';
            plusOriginalPrice.style.color = '#ff0000';
            plusOriginalPrice.style.fontWeight = 'bold';

            plusPlanSelect.innerHTML = `<option value="GovPrep - ${plusPlanOption} - ${selectedExam} - ${planDescription}">GovPrep - ${plusPlanOption} - ${selectedExam} - ${planDescription}</option>`;
            plusPlanSelect.value = `GovPrep - ${plusPlanOption} - ${selectedExam} - ${planDescription}`;
        }

        function handlePlusPlanButtonClick() {
            const selectedExam = plusExamSelect.value;

            if (selectedExam === 'default' || !pricingDetails[selectedExam]) {
                alert('Please select an exam');
                return;
            }

            const { price, months, plusOriginalPrice: orgprice } = pricingDetails[selectedExam];
            const planDescription = `${price} / ${months}`;
            const plusPlanOption = 'Plus';

            if (plusPlanButton.classList.contains('selected')) {
                // Uncheck: remove icon and deselect plan
                plusPlanButton.innerHTML = `<span class="text-center h6 p-3 text-white1" style="font-weight: bold; font-family: outfit; padding: 0 20px;">${planDescription}</span>`;
                plusPlanButton.classList.remove('selected');
                plusPlanButton.style.color = '';
                plusPlanButton.style.fontWeight = '';
                plusOriginalPrice.innerHTML = `<span style="font-weight: bold; font-family: outfit; margin-left:10px;">${orgprice}</span>`;
                plusOriginalPrice.style.color = '';
                plusOriginalPrice.style.fontWeight = '';
                plusPlanSelect.innerHTML = '<option value="">Choose Plan</option>';

                // Deselect Fresh plan button if selected
                if (planButton.classList.contains('selected')) {
                    planButton.click();
                }
            } else {
                // Check: add icon and select plan
                plusPlanButton.innerHTML = `<i class="fas fa-check-circle" style="margin-left:10px;"></i> <span class="text-center h6 p-3 text-white1" style="font-weight: bold; font-family: outfit; margin-right:10px; padding: 0 10px;">${planDescription}</span>`;
                plusPlanButton.classList.add('selected');
                plusPlanButton.style.color = '#000000';
                plusPlanButton.style.fontWeight = 'bold';
                plusOriginalPrice.innerHTML = `<span style="font-weight: bold; font-family: outfit; margin-left:10px;">${orgprice}</span>`;
                plusOriginalPrice.style.color = '#ff0000';
                plusOriginalPrice.style.fontWeight = 'bold';
                plusPlanSelect.innerHTML = `<option value="GovPrep - ${plusPlanOption} - ${selectedExam} - ${planDescription}">GovPrep - ${plusPlanOption} - ${selectedExam} - ${planDescription}</option>`;
                plusPlanSelect.value = `GovPrep - ${plusPlanOption} - ${selectedExam} - ${planDescription}`;

                // Deselect Fresh plan button if selected
                if (planButton.classList.contains('selected')) {
                    planButton.click();
                }
            }

            selectedExamInput.value = selectedExam;
            selectedPlanInput.value = planDescription;
            plusPlanForm.submit();

            localStorage.setItem('selectedExamPlus', selectedExam);
            localStorage.setItem('buttonStatePlus', plusPlanButton.classList.contains('selected') ? 'selected' : 'unselected');
        }

        plusPlanButton.addEventListener('click', handlePlusPlanButtonClick);
        plusExamSelect.addEventListener('change', updatePlusPlanDetails);

        const style = document.createElement('style');
        style.innerHTML = `
            .pulse {
                animation: pulse-animation 1s ease;
            }

            @keyframes pulse-animation {
                0% { transform: scale(1); }
                50% { transform: scale(1.05); }
                100% { transform: scale(1); }
            }
        `;
        document.head.appendChild(style);
    });
</script>



            </div>

        </div>

        <div class="col-md-4">

            <div class="card h-100">
                <div class="card-header" style="background-color:#191919;">
                    <img src="images/background/enter.svg" alt="" height="150px" width="200px">
                </div>
                <div class="card-body">

                    <div class="text-center">
                        <span class="custom-badge3 " style="font-size:14.75px; font-family:Outfit;">Best for Education Institutes</span>
                    </div>

                    <ul class="list-group mt-2">

                        <li class="d-flex align-items-baseline">
                            <i class="fas fa-circle-check text-success me-2"></i>
                            <ul class="card__list-description" style="font-family:Outfit; margin-top:10px;">
                                <li style="color:#109AFF; font-size:20px;">Enrollment Services</li>
                                <li class="card__list-subtitle" style="font-family:Outfit;">• Bulk Student Enrollment</li>
                                <li class="card__list-subtitle" style="font-family:Outfit;">• Individual Care with 1:1 Mentor</li>
                                <li class="card__list-subtitle" style="font-family:Outfit;">• Adv Progress Reports</li>
                                <li class="card__list-subtitle" style="font-family:Outfit;">• Close study circles</li>
                                <li class="card__list-subtitle" style="font-family:Outfit;">• Scheduling & Tasking on Individual
                                    abilities</li>
                            </ul>
                        <li class="d-flex align-items-baseline">
                            <i class="fas fa-circle-check text-success me-2"></i>
                            <ul class="card__list-description" style="font-family:Outfit; margin-top:10px;">
                                <li style="color:#109AFF; font-size:20px;">Support Services</li>
                                <li class="card__list-subtitle" style="font-family:Outfit;">• 24 / 7 Priority Email Support & Phone </li>
                                <li class="card__list-subtitle" style="font-family:Outfit;">• Personalised Analysis Onboarding</li>
                                <li class="card__list-subtitle" style="font-family:Outfit;">• Discussion Forum Access</li>

                            </ul>
                        </li>
                        <li class="d-flex align-items-baseline">
                            <i class="fas fa-circle-check text-success me-2"></i>
                            <ul class="card__list-description" style="font-family:Outfit; margin-top:10px;">
                                <li style="color:#109AFF; font-size:20px;">Evaluation & Reporting Services</li>
                                <li class="card__list-subtitle" style="font-family:Outfit;">• Specialised Evaluator Panel</li>
                                <li class="card__list-subtitle" style="font-family:Outfit;">• Periodical Performance Testing </li>
                                <li class="card__list-subtitle" style="font-family:Outfit;">• Report Student progress to
                                    Concern Institutes & Parents</li>

                            </ul>
                        </li>
                        </li>
                    </ul>

                </div>


                <div class="card-foote3 mt-5 d-flex align-items-center justify-content-center" style="background: linear-gradient(to bottom, #2BA6FF 0%, #0F9AFF 100%);">
                    <a href="contact-us.php" style='color:white; font-weight:bold; font-size:20px;'>Contact us</a>
                </div>

            </div>

        </div>





    </div>




</section>