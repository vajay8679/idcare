
<?php if ($this->ion_auth->is_admin() || $this->ion_auth->is_subAdmin() || $this->ion_auth->is_facilityManager()) { ?>
    </style>
    <style>
        .panel-bodyy{
            display:flex;
        }
        @media only screen and (max-width: 600px) {
            .panel-bodyy{
            flex-direction:column;
        } 
        .ajay1{
            width:190%;
        } 
        }


        @media only screen and (max-width: 600px) {
            .exportbutton{
                width: 100%;
        }  
        .exportbutton1{
            margin-top: -24px;
            float: left;
            width: 192px;
            font-size:11px;
            padding-left:4px;
            /* margin-left:-175 */
        }  
        .exportbutton2 {
            padding-bottom: 24px;
            margin-top: 10px;
        }
        }

        @media only screen and (max-width: 600px) {
            .exportbutton{
                width: 50%;
        }  
        .exportbutton3{
            margin-top: -24px;
            float: left;
            width: 192px;
        }  
        .exportbutton2 {
            padding-bottom: 24px;
            margin-top: 10px;
        }
        }


        @media only screen and (min-width: 668px) and (max-width: 1600px) {
        .exportbutton1{
            margin-top: -24px;
            margin-right: -33px;
            float: right;
            width: 246px;
        }  
        
        .exportbutton{
                max-width: 95%;
        }  
        .exportbutton2 {
            padding-bottom: 24px;
            margin-top: 10px;
        }
    }

    .cultur_source{
        padding: 0px 1px;
    }

    @media only screen and (min-width: 668px) and (max-width: 1600px) {
        .exportbutton3{
            margin-top: -24px;
            margin-right: -33px;
            float: right;
            width: 196px;
        }  
        
        .exportbutton{
                max-width: 95%;
        }  
        .exportbutton2 {
            padding-bottom: 24px;
            margin-top: 10px;
        }
    }

    .cultur_source{
        padding: 0px 1px;
    }


    #Graph-chart21{
        height: 540px;
    }
    </style>
    <!-- Page content -->
    <div id="page-content">
      <ul class="breadcrumb breadcrumb-top">
        <li>
            <a href="<?php echo site_url('pwfpanel'); ?>">Home</a>
        </li>
        <li>
            <a href=""><?php echo FAQ; ?></a>
        </li>
    </ul>
        <!--<div id="msg"></div>-->
        <!-- eShop Overview Block -->
        <?php
        $message = $this->session->flashdata('success');
        if (!empty($message)):
            ?><div class="alert alert-success">
                <?php echo $message; ?></div><?php endif; ?>
        <div class="block full">
          <div class="block-title">
            <h2><strong>Frequently Asked Questions?</strong></h2>
        </div>
            <div class="row">

                <div class="col-lg-12">
                    <div class="panel panel-success">

                        <div class="panel-heading">
                            <h4 class="panel-title"
                                data-toggle="collapse"
                                data-target="#collapseOne8">
                                <i class="fa fa-angle-down" style="font-size:20px"></i>
                             <!--  Antibiotic days saved sum cost saved -->
                             How Do I get started?
                                <a href="#" data-toggle="tooltip" data-placement="bottom"
                                   title="" data-original-title="This section represents the antibiotic days saved sum cost saved."
                                   class="red-tooltip"></a>
                            </h4>
                        </div>
                        <div id="collapseOne8" class="panel-collapse collapse">

                            <div class="panel-body">
                                <div class="col-lg-12 col-sm-12">
                                    <h4 class="text-justify"><strong> There are 4 easy steps:  </strong></h4>
                                    <h4 class="text-justify"><i class="fa fa-circle" style="font-size:12px"></i> Pick a 3 letter code that will act as your identifying code.  For example, if your facility is Northeast Care Facility, you can pick “NCF”.  Click “Care Unit” and add your code along with your facility name and email.  </h4>
                                    <h4 class="text-justify text-truncate"><i class="fa fa-circle" style="font-size:12px"></i> Add your providers that prescribe antibiotics in your facility.  Click “Provider MD” and add your provider names and emails   </h4>
                                    <h4 class="text-justify text-truncate"><i class="fa fa-circle" style="font-size:12px"></i> Add your steward who will be overseeing your antibiotic usage.  If they are not already listed, just click “MD Steward” and add their names and email as well as their password.  The selected steward can change their password at a later date.  The selected steward will be able to review your entries via the web or via the App on iOs/android.  </h4>
                                    <h4 class="text-justify text-truncate"><i class="fa fa-circle" style="font-size:12px"></i> (optional) If you wish to enter data from the App (iOs/android), you can click “Data Operator” where will enter your name, email, and password  </h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h4 class="panel-title"
                                data-toggle="collapse"
                                data-target="#collapseOne">
                                <i class="fa fa-angle-down" style="font-size:20px"></i>
                             How do I enter patient data?
                                <a href="#" data-toggle="tooltip" data-placement="bottom"
                                   title="" data-original-title="This section represents the antibiotic days saved sum cost saved."
                                   class="red-tooltip"></a>
                            </h4>
                        </div>
                        <div id="collapseOne" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="col-lg-12 col-sm-12">
                                    <h4 class="text-justify">Entering patient data is fast and easy.  A typical entry takes around 10 seconds to complete.  Click “Patient” and you can follow the prompts to enter the data for the steward to review</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h4 class="panel-title"
                                data-toggle="collapse"
                                data-target="#collapse3">
                                <i class="fa fa-angle-down" style="font-size:20px"></i>
                             What is “Patient ID”?
                                <a href="#" data-toggle="tooltip" data-placement="bottom"
                                   title="" data-original-title="This section represents the antibiotic days saved sum cost saved."
                                   class="red-tooltip"></a>
                            </h4>
                        </div>
                        <div id="collapse3" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="col-lg-12 col-sm-12">
                                    <h4 class="text-justify text-truncate">Patient ID is generally the medial record number or unique identifying number.  It is important the steward can recognize or search with the “patient ID” when they need to review said entry’s chart.</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h4 class="panel-title"
                                data-toggle="collapse"
                                data-target="#collapseTwo">
                                <i class="fa fa-angle-down" style="font-size:20px"></i>
                            What does “Infection Onset” mean?
                                <a href="#" data-toggle="tooltip" data-placement="bottom"
                                   title="" data-original-title="This section represents the antibiotic days saved sum cost saved."
                                   class="red-tooltip"></a>
                            </h4>
                        </div>
                        <div id="collapseTwo" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="col-lg-12 col-sm-12">
                                    <h4 class="text-justify">Infection onset is the site where the infection was acquired.  If the infection was acquired in the hospital, it will be labelled as a “Community-Acquired Infection (CAI)”.  If acquired in the facility, then it will labelled as “Hospital-Acquired Infection (HAI)”. </h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h4 class="panel-title"
                                data-toggle="collapse"
                                data-target="#collapse4">
                                <i class="fa fa-angle-down" style="font-size:20px"></i>
                            What antibiotics and infections are not included or tracked?
                                <a href="#" data-toggle="tooltip" data-placement="bottom"
                                   title="" data-original-title="This section represents the antibiotic days saved sum cost saved."
                                   class="red-tooltip"></a>
                            </h4>
                        </div>
                        <div id="collapse4" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="col-lg-12 col-sm-12">
                                    <h4 class="text-justify"><strong>Infections that are of concern are only those requiring systemic antibiotics by mouth or by vein.  So anything requiring topical therapy is not tracked.  Antibiotics/agents that are not listed:   </strong></h4>
                                    <h4 class="text-justify">rifaximin (xifaxan)</h4>
                                    <h4 class="text-justify">methenamine (hiprex) – not an antibiotic</h4>
                                    <h4 class="text-justify">anything topical in the eye or on the skin</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h4 class="panel-title"
                                data-toggle="collapse"
                                data-target="#collapse5">
                                <i class="fa fa-angle-down" style="font-size:20px"></i>
                            What is “ABX Checklist” and how do I use it?
                                <a href="#" data-toggle="tooltip" data-placement="bottom"
                                   title="" data-original-title="This section represents the antibiotic days saved sum cost saved."
                                   class="red-tooltip"></a>
                            </h4>
                        </div>
                        <div id="collapse5" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="col-lg-12 col-sm-12">
                                    <h4 class="text-justify">ABX checklist allows for you to verify the minimum criteria for starting antibiotic therapy.  It is NOT for Community-Acquired Infections (CAI) as they already are on antibiotics.  If your entry is for a CAI, then click “N/A”.</h4>
                                     <h4 class="text-justify">If, however, your entry is for a Hospital-Acquired Infection (HAI) then you will want to meet the metric for minimum criteria for starting antibiotic therapy via Loeb or the various McGeer Criteria (urinary tract infection – UTI, gastrointestinal tract infection – GITI, respiratory tract infection – RTI, and skin and soft tissue infections -SSTI).  Click the specific criteria for your entry and then you will want to click “Print ABX Checklist Form”.</h4>
                                      <h4 class="text-justify">You will then be connected to a separate window reflecting the above checklist criteria where you can type in their name, location, and the date (or you can do this manually after printing).  Once you run through the checklist for your specific diagnosis, you can determine if criteria are “Met” or “Not Met”.  Once completed, you can either <strong>save</strong> to your hard drive or <strong>print</strong> and keep the printed file in a folder.  You can then <strong>close</strong> the file which takes you back to the patient entry menu.</h4>
                                      <h4 class="text-justify">Finally, Click whether “Criteria Met” was “Yes” or “No”.</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h4 class="panel-title"
                                data-toggle="collapse"
                                data-target="#collapse6">
                                <i class="fa fa-angle-down" style="font-size:20px"></i>
                           What is prophylaxis and how do I use it in data entry? 
                                <a href="#" data-toggle="tooltip" data-placement="bottom"
                                   title="" data-original-title="This section represents the antibiotic days saved sum cost saved."
                                   class="red-tooltip"></a>
                            </h4>
                        </div>
                        <div id="collapse6" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="col-lg-12 col-sm-12">
                                    <h4 class="text-justify"><strong>Prophylaxis refers to antibiotic usage for someone not currently infected but rather its utility is to prevent an infection.  The dosing is often different and adjusted in the system.  For example: </strong></h4>
                                    <h4 class="text-justify"><strong>C.diff Prophylaxis – </strong>often ½ typical dose to prevent a recurrence.  <strong>Used with Vancomycin PO prophylaxis </strong> </h4>
                                    <h4 class="text-justify"><strong>Herpes Prophylaxis –</strong> Often ¼ to ½ typical dose to prevent recurrence.  <strong>Used with Acyclovir, Famciclovir or Valacyclovir prophylaxis.</strong>  If a long term patient, please enter this data every month.  If subacute, enter “30” as the number of days as that reflects the typical average length of stay  </h4>
                                    <h4 class="text-justify"><strong>Influenza prophylaxis – </strong>½ typical dose to prevent influenza.  <strong>Used with influenza prophylaxis.</strong> </h4>
                                    <h4 class="text-justify"><strong>Prophylaxis – Primary or Secondary – </strong>Dosing varies.  But this is anyone on “longer than normal” or chronically on an antibiotic to prevent an infection.   If a long term patient, please enter this data every month.  If subacute, enter “30” as the number of days as that reflects the typical average length of stay.Prophylaxis is NOT used for any other circumstance other than in preventing an infection.  If using an empiric antibiotic for concern of a UTI or RTI, that would not be considered prophylactic use and, in fact, one is ACTUALLY treating a UTI or RTI.  </h4>
                                   
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h4 class="panel-title"
                                data-toggle="collapse"
                                data-target="#collapse7">
                                <i class="fa fa-angle-down" style="font-size:20px"></i>
                            Do I use” Primary or Secondary Prophylaxis” as the diagnosis for someone being considered and treated for an infection?  
                                <a href="#" data-toggle="tooltip" data-placement="bottom"
                                   title="" data-original-title="This section represents the antibiotic days saved sum cost saved."
                                   class="red-tooltip"></a>
                            </h4>
                        </div>
                        <div id="collapse7" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="col-lg-12 col-sm-12">
                                    <h4 class="text-justify">No.  “Prophylaxis – Primary or Secondary” is used only for those to prevent an infection not treat an infection.</h4>
                                    
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h4 class="panel-title"
                                data-toggle="collapse"
                                data-target="#collapse8">
                                <i class="fa fa-angle-down" style="font-size:20px"></i>
                            How do I print my Antibiotic Surveillance list? 
                                <a href="#" data-toggle="tooltip" data-placement="bottom"
                                   title="" data-original-title="This section represents the antibiotic days saved sum cost saved."
                                   class="red-tooltip"></a>
                            </h4>
                        </div>
                        <div id="collapse8" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="col-lg-12 col-sm-12">
                                    <h4 class="text-justify">Under the main menu click “Patient” and then “Select Care Unit”, your date range, then export which will convert to an excel file..  You will then get your excel document reflecting those entries in your date range.</h4>
                                    
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h4 class="panel-title"
                                data-toggle="collapse"
                                data-target="#collapse9">
                                <i class="fa fa-angle-down" style="font-size:20px"></i>
                           How do I print my reports? 
                                <a href="#" data-toggle="tooltip" data-placement="bottom"
                                   title="" data-original-title="This section represents the antibiotic days saved sum cost saved."
                                   class="red-tooltip"></a>
                            </h4>
                        </div>
                        <div id="collapse9" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="col-lg-12 col-sm-12">
                                    <h4 class="text-justify">Under the main menu, click “Reports Summary” and then “Select Care Unit” and you may save or print out a pdf for a specific date range or a specific month under “Download Monthly Reports” or a specific quarte/year under “Download Quarterly/Yearly Reports”</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h4 class="panel-title"
                                data-toggle="collapse"
                                data-target="#collapse10">
                                <i class="fa fa-angle-down" style="font-size:20px"></i>
                                How do I simplify my NHSN reporting? 
                                <a href="#" data-toggle="tooltip" data-placement="bottom"
                                   title="" data-original-title="This section represents the antibiotic days saved sum cost saved."
                                   class="red-tooltip"></a>
                            </h4>
                        </div>
                        <div id="collapse10" class="panel-collapse collapse">
                            <div class="panel-body">
                                <div class="col-lg-12 col-sm-12">
                                    <h4 class="text-justify">We have simplified the reporting to make it easier to “rank” your selections placing an asterisk to 
prioritize organisms and diagnoses.  For example:  the selection “*CDiff-NHSN” as your diagnosis will you to prioritize this on your excel document to simplify the process of NHSN data entry.</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
          
        </div>
    </div>
    
<?php } ?>


