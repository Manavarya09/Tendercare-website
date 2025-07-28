<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Book a Demo | Tendercare</title>
  <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/vendor/bootstrap-icons/bootstrap-icons.css">
  <link rel="stylesheet" href="assets/css/main.css">
  <style>
    .custom-country-dropdown {
      position: relative;
      width: 90px;
      user-select: none;
      z-index: 10;
    }
    .custom-country-selected {
      border: 1px solid #ced4da;
      border-right: none;
      border-radius: 8px 0 0 8px;
      background: #fff;
      padding: 0.55rem 0.7rem;
      font-size: 1.08rem;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 0.3em;
      height: 100%;
    }
    .custom-country-list {
      display: none;
      position: absolute;
      top: 100%;
      left: 0;
      width: 100%;
      background: #fff;
      border: 1px solid #ced4da;
      border-radius: 0 0 8px 8px;
      max-height: 220px;
      overflow-y: auto;
      box-shadow: 0 8px 24px rgba(0,0,0,0.08);
    }
    .custom-country-list.open {
      display: block;
    }
    .custom-country-option {
      padding: 0.5rem 0.7rem;
      font-size: 1.08rem;
      cursor: pointer;
      display: flex;
      align-items: center;
      gap: 0.3em;
      transition: background 0.15s;
    }
    .custom-country-option:hover, .custom-country-option.selected {
      background: #f7f7f7;
    }
    .country-phone-group {
      display: flex;
      align-items: stretch;
      gap: 0;
      position: relative;
    }
    .country-phone-input {
      border-top-left-radius: 0;
      border-bottom-left-radius: 0;
      border-left: none;
      border-color: #ced4da;
      background: #fff;
      height: 100%;
      box-shadow: none;
      z-index: 2;
    }
    .sent-message {
      display: none;
      background: #e6ffed;
      color: #218838;
      border: 2px solid #28a745;
      border-radius: 8px;
      padding: 16px;
      text-align: center;
      font-weight: 600;
      font-size: 1.1em;
      margin-top: 18px;
      box-shadow: 0 2px 8px rgba(40,167,69,0.08);
    }
  </style>
</head>
<body>
  <div class="demo-form-bg-flow">
    <svg viewBox="0 0 1440 180" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
      <path class="wave1" d="M0,120 C360,180 1080,60 1440,120 L1440,180 L0,180 Z" fill="#ffb347" fill-opacity="0.18"/>
      <path class="wave2" d="M0,160 C480,100 960,200 1440,140 L1440,180 L0,180 Z" fill="#ff6600" fill-opacity="0.12"/>
    </svg>
  </div>
  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="header-container container-fluid container-xl position-relative d-flex align-items-center justify-content-between">
      <a href="index.html" class="logo d-flex align-items-center me-auto me-xl-0">
        <img src="assets/img/tendercare-logo.png" alt="Tendercare Logo" class="logo-img">
      </a>
      <nav id="navmenu" class="navmenu">
        <ul>
          <div class="mobile-nav-close d-xl-none">
            <i class="bi bi-x"></i>
          </div>
          <li><a href="index.html#hero">Home</a></li>
          <li><a href="index.html#products">Products</a></li>
          <li><a href="index.html#features">Features</a></li>
          <li><a href="index.html#testimonials">Testimonials</a></li>
          <li><a href="about.html">About Us</a></li>
          <li><a href="index.html#contact">Contact</a></li>
          <li class="d-xl-none mobile-demo-btn">
            <a href="demo-form.php" class="btn-getstarted-mobile active">
              <span>Book a Demo</span>
            </a>
          </li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>
      <a class="btn-getstarted" href="demo-form.html"><span>Book a Demo</span></a>
      <!-- committed by Manav Arya Singh -->
    </div>
  </header>
  <main class="main" style="margin-top: 100px;">
    <section class="container py-5 demo-form-section" style="position:relative;">
      <div class="demo-form-bg-blob"></div>
      <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-8">
          <div class="card shadow-sm border-0 demo-form-card">
            <div class="card-body p-4">
              <div class="text-center mb-4">
                <img src="assets/img/tendercare-logo.png" alt="Tendercare Logo" style="height: 48px;">
                <h2 class="mt-3 mb-1" style="font-weight: 700;">Customer Inquiry Form</h2>
              </div>
              
              <!-- Progress Bar -->
              <div class="progress-container mb-4">
                <div class="progress-steps">
                  <div class="progress-step active" data-step="1">
                    <div class="step-number">1</div>
                    <div class="step-label">General Info</div>
                  </div>
                  <div class="progress-step" data-step="2">
                    <div class="step-number">2</div>
                    <div class="step-label">Inquiry</div>
                  </div>
                  <div class="progress-step" data-step="3">
                    <div class="step-number">3</div>
                    <div class="step-label">Technical</div>
                  </div>
                </div>
                <div class="progress-bar-wrapper">
                  <div class="progress-bar" id="formProgress">
                    <div class="progress-fill" id="progressFill"></div>
                  </div>
                  <div class="progress-text" id="progressText">0% Complete</div>
                </div>
              </div>
              
              <div class="mb-4">
                <div style="height: 8px; background: linear-gradient(90deg, #ff6600 0%, #ffb347 100%); border-radius: 6px; width: 100%; margin-bottom: 1.5rem; opacity: 0.22;"></div>
              </div>
              <form action="book-demo.php" method="post">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                <input type="text" name="website" style="display:none" tabindex="-1" autocomplete="off">
                <!-- Honeypot field (hidden from users) -->
                <div style="display:none;">
                  <label for="website_hp">Leave this field empty</label>
                  <input type="text" name="website_hp" id="website_hp" autocomplete="off">
                </div>
                <div class="mb-4 demo-form-section-title demo-form-fadein">1. General Information</div>
                <div class="row demo-form-fadein" style="row-gap: 0.4rem;">
                  <div class="col-12">
                    <div class="demo-form-floating">
                      <span class="demo-form-icon"><i class="bi bi-person"></i></span>
                      <input type="text" class="form-control demo-form-input" id="customerName" name="customerName" placeholder=" " autocomplete="name" required>
                      <label for="customerName">Customer Name / Contact Person <span class="text-danger">*</span></label>
                      <div class="valid-feedback">Beautiful Name:)</div>
                      <div class="invalid-feedback">Please enter a name.</div>
                    </div>
                  </div>
                  <div class="col-12">
                    <div class="demo-form-floating">
                      <span class="demo-form-icon"><i class="bi bi-building"></i></span>
                      <input type="text" class="form-control demo-form-input" id="orgName" name="orgName" placeholder=" " autocomplete="organization" required>
                      <label for="orgName">Organization / Clinic Name <span class="text-danger">*</span></label>
                      <div class="valid-feedback">Looks good!</div>
                      <div class="invalid-feedback">Please enter an organization name.</div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="demo-form-floating">
                      <span class="demo-form-icon"><i class="bi bi-envelope"></i></span>
                      <input type="email" class="form-control demo-form-input" id="contactDetails" name="contactDetails" placeholder=" " autocomplete="email" required>
                      <label for="contactDetails">Email <span class="text-danger">*</span></label>
                      <div class="valid-feedback">Valid email!</div>
                      <div class="invalid-feedback">Please enter a valid email.</div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="country-phone-group demo-form-floating" style="width:100%;">
                      <div class="custom-country-dropdown" id="customCountryDropdown">
                        <div class="custom-country-selected" id="selectedCountry">
                          <span class="flag">🇦🇪</span> <span class="code">+971</span>
                        </div>
                        <div class="custom-country-list" id="countryList">
                          <div id="countryOptions">
                            <div class="custom-country-option selected" data-flag="🇦🇪" data-code="+971"><span class="flag">🇦🇪</span> <span class="code">+971</span></div>
                            <div class="custom-country-option" data-flag="🇺🇸" data-code="+1"><span class="flag">🇺🇸</span> <span class="code">+1</span></div>
                            <div class="custom-country-option" data-flag="🇬🇧" data-code="+44"><span class="flag">🇬🇧</span> <span class="code">+44</span></div>
                            <div class="custom-country-option" data-flag="🇮🇳" data-code="+91"><span class="flag">🇮🇳</span> <span class="code">+91</span></div>
                            <div class="custom-country-option" data-flag="🇦🇺" data-code="+61"><span class="flag">🇦🇺</span> <span class="code">+61</span></div>
                            <div class="custom-country-option" data-flag="🇯🇵" data-code="+81"><span class="flag">🇯🇵</span> <span class="code">+81</span></div>
                            <div class="custom-country-option" data-flag="🇩🇪" data-code="+49"><span class="flag">🇩🇪</span> <span class="code">+49</span></div>
                            <div class="custom-country-option" data-flag="🇫🇷" data-code="+33"><span class="flag">🇫🇷</span> <span class="code">+33</span></div>
                            <div class="custom-country-option" data-flag="🇮🇹" data-code="+39"><span class="flag">🇮🇹</span> <span class="code">+39</span></div>
                            <div class="custom-country-option" data-flag="🇨🇳" data-code="+86"><span class="flag">🇨🇳</span> <span class="code">+86</span></div>
                            <div class="custom-country-option" data-flag="🇷🇺" data-code="+7"><span class="flag">🇷🇺</span> <span class="code">+7</span></div>
                            <div class="custom-country-option" data-flag="🇸🇦" data-code="+966"><span class="flag">🇸🇦</span> <span class="code">+966</span></div>
                            <div class="custom-country-option" data-flag="🇶🇦" data-code="+974"><span class="flag">🇶🇦</span> <span class="code">+974</span></div>
                            <div class="custom-country-option" data-flag="🇧🇭" data-code="+973"><span class="flag">🇧🇭</span> <span class="code">+973</span></div>
                            <div class="custom-country-option" data-flag="🇰🇼" data-code="+965"><span class="flag">🇰🇼</span> <span class="code">+965</span></div>
                            <div class="custom-country-option" data-flag="🇪🇬" data-code="+20"><span class="flag">🇪🇬</span> <span class="code">+20</span></div>
                            <div class="custom-country-option" data-flag="🇧🇩" data-code="+880"><span class="flag">🇧🇩</span> <span class="code">+880</span></div>
                            <div class="custom-country-option" data-flag="🇵🇰" data-code="+92"><span class="flag">🇵🇰</span> <span class="code">+92</span></div>
                            <div class="custom-country-option" data-flag="🇳🇬" data-code="+234"><span class="flag">🇳🇬</span> <span class="code">+234</span></div>
                            <div class="custom-country-option" data-flag="🇿🇦" data-code="+27"><span class="flag">🇿🇦</span> <span class="code">+27</span></div>
                            <div class="custom-country-option" data-flag="🇪🇸" data-code="+34"><span class="flag">🇪🇸</span> <span class="code">+34</span></div>
                            <div class="custom-country-option" data-flag="🇵🇹" data-code="+351"><span class="flag">🇵🇹</span> <span class="code">+351</span></div>
                            <div class="custom-country-option" data-flag="🇹🇷" data-code="+90"><span class="flag">🇹🇷</span> <span class="code">+90</span></div>
                            <div class="custom-country-option" data-flag="🇰🇷" data-code="+82"><span class="flag">🇰🇷</span> <span class="code">+82</span></div>
                            <div class="custom-country-option" data-flag="🇮🇩" data-code="+62"><span class="flag">🇮🇩</span> <span class="code">+62</span></div>
                            <div class="custom-country-option" data-flag="🇵🇭" data-code="+63"><span class="flag">🇵🇭</span> <span class="code">+63</span></div>
                            <div class="custom-country-option" data-flag="🇹🇭" data-code="+66"><span class="flag">🇹🇭</span> <span class="code">+66</span></div>
                            <div class="custom-country-option" data-flag="🇸🇬" data-code="+65"><span class="flag">🇸🇬</span> <span class="code">+65</span></div>
                            <div class="custom-country-option" data-flag="🇲🇾" data-code="+60"><span class="flag">🇲🇾</span> <span class="code">+60</span></div>
                            <div class="custom-country-option" data-flag="🇳🇿" data-code="+64"><span class="flag">🇳🇿</span> <span class="code">+64</span></div>
                            <div class="custom-country-option" data-flag="🇧🇷" data-code="+55"><span class="flag">🇧🇷</span> <span class="code">+55</span></div>
                            <div class="custom-country-option" data-flag="🇲🇽" data-code="+52"><span class="flag">🇲🇽</span> <span class="code">+52</span></div>
                            <div class="custom-country-option" data-flag="🇨🇦" data-code="+1"><span class="flag">🇨🇦</span> <span class="code">+1</span></div>
                            <div class="custom-country-option" data-flag="🇮🇪" data-code="+353"><span class="flag">🇮🇪</span> <span class="code">+353</span></div>
                            <div class="custom-country-option" data-flag="🇫🇮" data-code="+358"><span class="flag">🇫🇮</span> <span class="code">+358</span></div>
                            <div class="custom-country-option" data-flag="🇸🇪" data-code="+46"><span class="flag">🇸🇪</span> <span class="code">+46</span></div>
                            <div class="custom-country-option" data-flag="🇳🇴" data-code="+47"><span class="flag">🇳🇴</span> <span class="code">+47</span></div>
                            <div class="custom-country-option" data-flag="🇵🇱" data-code="+48"><span class="flag">🇵🇱</span> <span class="code">+48</span></div>
                            <div class="custom-country-option" data-flag="🇦🇹" data-code="+43"><span class="flag">🇦🇹</span> <span class="code">+43</span></div>
                            <div class="custom-country-option" data-flag="🇨🇭" data-code="+41"><span class="flag">🇨🇭</span> <span class="code">+41</span></div>
                            <div class="custom-country-option" data-flag="🇳🇱" data-code="+31"><span class="flag">🇳🇱</span> <span class="code">+31</span></div>
                            <div class="custom-country-option" data-flag="🇧🇪" data-code="+32"><span class="flag">🇧🇪</span> <span class="code">+32</span></div>
                            <div class="custom-country-option" data-flag="🇨🇿" data-code="+420"><span class="flag">🇨🇿</span> <span class="code">+420</span></div>
                            <div class="custom-country-option" data-flag="🇸🇰" data-code="+421"><span class="flag">🇸🇰</span> <span class="code">+421</span></div>
                            <div class="custom-country-option" data-flag="🇭🇺" data-code="+36"><span class="flag">🇭🇺</span> <span class="code">+36</span></div>
                            <div class="custom-country-option" data-flag="🇸🇮" data-code="+386"><span class="flag">🇸🇮</span> <span class="code">+386</span></div>
                            <div class="custom-country-option" data-flag="🇭🇷" data-code="+385"><span class="flag">🇭🇷</span> <span class="code">+385</span></div>
                            <div class="custom-country-option" data-flag="🇷🇴" data-code="+40"><span class="flag">🇷🇴</span> <span class="code">+40</span></div>
                            <div class="custom-country-option" data-flag="🇺🇦" data-code="+380"><span class="flag">🇺🇦</span> <span class="code">+380</span></div>
                          </div>
                        </div>
                      </div>
                      <input type="number" class="form-control demo-form-input no-spinner country-phone-input" id="preferredContact" name="preferredContact" placeholder=" " min="0" step="1" pattern="[0-9]*" inputmode="tel" autocomplete="tel" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                      <label for="preferredContact">Contact Number <span class="text-danger">*</span></label>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="demo-form-floating">
                      <span class="demo-form-icon"><i class="bi bi-person-lines-fill"></i></span>
                      <input type="number" class="form-control demo-form-input" id="numUsers" name="numUsers" placeholder=" " min="0" step="1" pattern="[0-9]*" inputmode="numeric" autocomplete="off" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                      <label for="numUsers">Number of Users <span class="text-danger">*</span></label>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="demo-form-floating">
                      <span class="demo-form-icon"><i class="bi bi-people"></i></span>
                      <input type="number" class="form-control demo-form-input" id="numDoctors" name="numDoctors" placeholder=" " min="0" step="1" pattern="[0-9]*" inputmode="numeric" autocomplete="off" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                      <label for="numDoctors">Number of Doctors <span class="text-danger">*</span></label>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="demo-form-floating">
                      <span class="demo-form-icon"><i class="bi bi-heart-pulse"></i></span>
                      <input type="text" class="form-control demo-form-input" id="specialties" name="specialties" placeholder=" " autocomplete="off">
                      <label for="specialties">Medical Specialties <span class="text-secondary"></span></label>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="demo-form-floating">
                      <span class="demo-form-icon"><i class="bi bi-geo-alt"></i></span>
                      <input type="number" class="form-control demo-form-input" id="numBranches" name="numBranches" placeholder=" " min="0" step="1" pattern="[0-9]*" inputmode="numeric" autocomplete="off" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                      <label for="numBranches">Number of Branches / Locations <span class="text-secondary"></span></label>
                    </div>
                  </div>
                </div>
                <hr class="demo-form-divider">
                <div class="mb-4 demo-form-section-title demo-form-fadein">2. Nature of Inquiry</div>
                <div class="mb-3 row demo-form-fadein">
                  <div class="col-md-6">
                    <div class="form-check mb-2">
                      <input class="form-check-input demo-form-check-input" type="checkbox" id="newPurchase" name="newPurchase">
                      <label class="demo-form-check-label" for="newPurchase">New Purchase</label>
                    </div>
                    <div class="form-check mb-2">
                      <input class="form-check-input demo-form-check-input" type="checkbox" id="demoRequest" name="demoRequest">
                      <label class="demo-form-check-label" for="demoRequest">Demo Request</label>
                    </div>
                    <div class="form-check mb-2">
                      <input class="form-check-input demo-form-check-input" type="checkbox" id="softwareUpgrade" name="softwareUpgrade">
                      <label class="demo-form-check-label" for="softwareUpgrade">Software Upgrade</label>
                    </div>
                    <div class="form-check mb-2">
                      <input class="form-check-input demo-form-check-input" type="checkbox" id="integrationInquiry" name="integrationInquiry">
                      <label class="demo-form-check-label" for="integrationInquiry">Integration Inquiry</label>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-check mb-2">
                      <input class="form-check-input demo-form-check-input" type="checkbox" id="pricingRequest" name="pricingRequest">
                      <label class="demo-form-check-label" for="pricingRequest">Pricing / Quotation Request</label>
                    </div>
                    <div class="form-check mb-2">
                      <input class="form-check-input demo-form-check-input" type="checkbox" id="customizationRequest" name="customizationRequest">
                      <label class="demo-form-check-label" for="customizationRequest">Customization Request</label>
                    </div>
                    <div class="form-check d-flex align-items-center mb-2">
                      <input class="form-check-input demo-form-check-input me-2" type="checkbox" id="otherInquiry" name="otherInquiry">
                      <label class="demo-form-check-label flex-grow-1" for="otherInquiry">Other:</label>
                      <input type="text" class="form-control demo-form-input ms-2" name="otherInquiryText" style="max-width: 180px;">
                    </div>
                  </div>
                </div>
                <hr class="demo-form-divider">
                <div class="mb-4 demo-form-section-title demo-form-fadein">3. Technical Requirements</div>
                <div class="row demo-form-fadein">
                  <div class="col-md-12">
                    <div class="demo-form-floating">
                      <span class="demo-form-icon"><i class="bi bi-pc-display"></i></span>
                      <input type="text" class="form-control demo-form-input" id="existingSoftware" name="existingSoftware" placeholder=" ">
                      <label for="existingSoftware">Existing Software/Systems (if any)</label>
                    </div>
                  </div>
                </div>
                <div class="mb-3 row demo-form-fadein">
                  <div class="col-md-6">
                    <label class="form-label fw-bold demo-form-label">Server Preference:</label>
                    <div class="form-check">
                      <input class="form-check-input demo-form-check-input" type="radio" name="serverPref" id="ownServer" value="Own Server">
                      <label class="demo-form-check-label" for="ownServer">Own Server</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input demo-form-check-input" type="radio" name="serverPref" id="cloud" value="Cloud">
                      <label class="demo-form-check-label" for="cloud">Cloud</label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input demo-form-check-input" type="radio" name="serverPref" id="notSure" value="Not Sure">
                      <label class="demo-form-check-label" for="notSure">Not Sure</label>
                    </div>
                  </div>
                </div>
                <div class="row demo-form-fadein">
                  <div class="col-md-6">
                    <div class="demo-form-floating">
                      <span class="demo-form-icon"><i class="bi bi-calendar-event"></i></span>
                      <input type="text" class="form-control demo-form-input" id="demoDate" name="demoDate" placeholder=" " autocomplete="off">
                      <label for="demoDate">Preferred Demo Day/Date</label>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="demo-form-floating">
                      <span class="demo-form-icon"><i class="bi bi-cash-coin"></i></span>
                      <input type="text" class="form-control demo-form-input" id="budget" name="budget" placeholder=" ">
                      <label for="budget">Budget (Optional)</label>
                    </div>
                  </div>
                </div>
                <div class="mb-2">
                  <small class="text-muted fst-italic">Note: Additional information may be required depending on the medical specialty or size of your organization.</small>
                </div>
                <div class="text-center mt-4">
                  <button type="submit" class="btn demo-form-btn px-5">Submit</button>
                </div>
                <div class="loading" style="display:none; color:#ff6600; text-align:center; margin-top:16px;">Loading...</div>
                <div class="error-message" style="display:none; color:#d9534f; text-align:center; margin-top:16px;"></div>
                <div class="sent-message" id="demoFormThankYou" style="display:none; color:#ff6600; font-size:1.2em; margin-top:1.5em;">Thank you for your valuable time.</div>
              </form>
              <div class="alert alert-success mt-4 d-none" id="formSuccessMsg" role="alert">
                <strong>Thank you!</strong> Your inquiry has been submitted. Our team will contact you within 24 hours. If you need urgent assistance, please call us at +971 (4) 43267077.
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
  <footer id="footer" class="footer">
    <div class="container copyright text-center mt-4 position-relative">
      <p class="mb-0">© Copyright <span>Tendercare</span> All Rights Reserved</p>
      <div style="margin-top: 8px; font-size: 1.1em; color: #ff6600; font-weight: bold; letter-spacing: 1px;"></div>
      <a href="faq.html" target="_blank" class="faq-footer-link">FAQ</a>
    </div>
    <style>
      .faq-footer-link {
        position: absolute;
        right: 18px;
        bottom: 18px;
        background: linear-gradient(90deg, #ff6600 0%, #ffb347 100%);
        color: #fff;
        font-weight: 600;
        padding: 10px 26px;
        border-radius: 24px 8px 24px 8px;
        font-size: 1.08em;
        box-shadow: 0 4px 18px rgba(255,102,0,0.13);
        text-decoration: none;
        transition: background 0.2s, box-shadow 0.2s, color 0.2s;
        z-index: 10;
      }
      .faq-footer-link:hover {
        background: linear-gradient(90deg, #ffb347 0%, #ff6600 100%);
        color: #fff6f0;
        box-shadow: 0 8px 32px rgba(255,102,0,0.22);
      }
    </style>
  </footer>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/js/main.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script>
  // Progress bar functionality
  function updateProgress() {
    const totalFields = 15; // Total number of form fields
    let completedFields = 0;
    
    // Check text inputs
    document.querySelectorAll('.demo-form-input').forEach(function(input) {
      if (input.value.trim() !== '') {
        completedFields++;
      }
    });
    
    // Check checkboxes
    document.querySelectorAll('.demo-form-check-input[type="checkbox"]').forEach(function(checkbox) {
      if (checkbox.checked) {
        completedFields++;
      }
    });
    
    // Check radio buttons
    document.querySelectorAll('.demo-form-check-input[type="radio"]').forEach(function(radio) {
      if (radio.checked) {
        completedFields++;
      }
    });
    
    const progressPercentage = Math.round((completedFields / totalFields) * 100);
    const progressFill = document.getElementById('progressFill');
    const progressText = document.getElementById('progressText');
    
    progressFill.style.width = progressPercentage + '%';
    progressText.textContent = progressPercentage + '% Complete';
    
    // Update step indicators
    updateStepIndicators(progressPercentage);
  }
  
  function updateStepIndicators(percentage) {
    const steps = document.querySelectorAll('.progress-step');
    
    if (percentage < 33) {
      steps[0].classList.add('active');
      steps[1].classList.remove('active');
      steps[2].classList.remove('active');
    } else if (percentage < 66) {
      steps[0].classList.add('active');
      steps[1].classList.add('active');
      steps[2].classList.remove('active');
    } else {
      steps[0].classList.add('active');
      steps[1].classList.add('active');
      steps[2].classList.add('active');
    }
  }
  
  // Section fade-in on scroll
  function onScrollFadeIn() {
    document.querySelectorAll('.demo-form-fadein').forEach(function(el) {
      var rect = el.getBoundingClientRect();
      if (rect.top < window.innerHeight - 60) {
        el.classList.add('visible');
      }
    });
  }
  window.addEventListener('scroll', onScrollFadeIn);
  window.addEventListener('DOMContentLoaded', onScrollFadeIn);
  
  // Button ripple effect
  document.querySelectorAll('.demo-form-btn').forEach(function(btn) {
    btn.addEventListener('click', function(e) {
      var ripple = document.createElement('span');
      ripple.className = 'ripple';
      ripple.style.left = (e.offsetX) + 'px';
      ripple.style.top = (e.offsetY) + 'px';
      btn.appendChild(ripple);
      setTimeout(function() { ripple.remove(); }, 600);
    });
  });
  
  // Real-time validation feedback and progress update
  document.querySelectorAll('.demo-form-input, .demo-form-check-input').forEach(function(input) {
    input.addEventListener('input', function() {
      if (input.type === 'text' || input.type === 'email' || input.type === 'number') {
        if (input.checkValidity()) {
          input.classList.add('is-valid');
          input.classList.remove('is-invalid');
        } else {
          input.classList.remove('is-valid');
          input.classList.add('is-invalid');
        }
      }
      updateProgress();
    });
    
    input.addEventListener('change', function() {
      updateProgress();
    });
  });
  
  // Initialize progress on page load
  document.addEventListener('DOMContentLoaded', function() {
    updateProgress();
  });

  // Custom country dropdown logic
  const dropdown = document.getElementById('customCountryDropdown');
  const selected = document.getElementById('selectedCountry');
  const list = document.getElementById('countryList');
  let options = list.querySelectorAll('.custom-country-option');

  selected.onclick = function(e) {
    list.classList.toggle('open');
    e.stopPropagation();
  };
  options.forEach(opt => {
    opt.onclick = function() {
      options.forEach(o => o.classList.remove('selected'));
      this.classList.add('selected');
      selected.innerHTML = `<span class='flag'>${this.dataset.flag}</span> <span class='code'>${this.dataset.code}</span>`;
      list.classList.remove('open');
    };
  });
  document.addEventListener('click', function() {
    list.classList.remove('open');
  });
  </script>
  <script>
document.addEventListener('DOMContentLoaded', function() {
  var demoForm = document.querySelector('form[action="book-demo.php"]');
  var thankYouMsg = document.getElementById('demoFormThankYou');
  
  if (demoForm && thankYouMsg) {
    demoForm.addEventListener('submit', function(e) {
      e.preventDefault();
      
      const submitButton = demoForm.querySelector('button[type="submit"]');
      const loadingIndicator = demoForm.querySelector('.loading');

      if (submitButton) submitButton.style.display = 'none';
      if (loadingIndicator) loadingIndicator.style.display = 'block';

      var formData = new FormData(demoForm);
      fetch('book-demo.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.text())
      .then(data => {
        if (loadingIndicator) loadingIndicator.style.display = 'none';

        if (data.trim() === 'OK') {
          // Hide the button and show the thank you message inside the form
          thankYouMsg.style.display = 'block';
        } else {
          // Show the error and bring back the submit button
          var err = demoForm.querySelector('.error-message');
          if (err) {
            err.textContent = data;
            err.style.display = 'block';
          }
          if (submitButton) submitButton.style.display = 'block';
        }
      })
      .catch((error) => {
        if (loadingIndicator) loadingIndicator.style.display = 'none';
        var err = demoForm.querySelector('.error-message');
        if (err) {
            err.textContent = 'A network error occurred. Please try again.';
            err.style.display = 'block';
        }
        if (submitButton) submitButton.style.display = 'block';
      });
    });
  }
});
</script>
</body>
</html> 