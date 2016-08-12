<?php
require_once("pcrCommon.php");

$action = isset($_REQUEST['pcraction']) ? $_REQUEST['pcraction'] : 'getsearch';
$pcrId = "";

if (!isset($_COOKIE['pcr-id']))
{
    $pcrId = perform_authentication();   
}else{
    $pcrId = $_COOKIE['pcr-id'];
}

switch($action)
{
    case "getsearch":
        echo get_job_search();
        break;
    case "performsearch":
        echo perform_search();
        break;
    case "getposition":
        echo get_position();
        break;
    case "createcandidate":
        echo create_candidate();
        break;
    case "deletecandidate":
        echo delete_candidate();
    case "createresume":
        echo create_resume();
        break;
    case "create_candidate_attachment":
        echo create_candidate_attachment();
        break;
    case "defaultcompany":
        echo get_default_company();
        break;
    case "getcandidate":
        echo get_candidate();
        break;
    case "searchcandidate":
        echo search_candidate();
}


?>



<?php

//************* Delete Functions ********************************* //
function delete_candidate(){
    $pcrSession = get_pcr_session_obj();
    
    $record = new stdClass();
    $record->RecordId = 122939568408288;
    $record->RecordType = "Candidate";
    
    $container = new stdClass();
    $container->session = $pcrSession;
    $container->Record = $record;
    
    $client = get_soap_client();
    try{
        $result = $client->DeleteRecord($container);
        //print_r($result);
        return $result->DeleteRecordResult;
    }catch(Exception $e){
        echo "Fault: ".$e->getMessage() . "\n";
    }
}

//************* Create Functions ********************************* //
function create_candidate(){
    $pcrSession = get_pcr_session_obj();
    
    $candidate = new stdClass();
    $candidate->CompanyId = get_default_company();
    $candidate->FirstName = "Jean Luc";
    $candidate->LastName = "Picard";
    $candidate->Identification = "password";
    $candidate->EmailAddress = "cpicard@starfleet.com";
    $candidate->Group = "VisibleOnInternet";
    $candidate->Address = "USS Enterprise Room 1";
    $candidate->City = "La Barre";
    $candidate->State = "";
    $candidate->Country = "France";
    $candidate->Zip = "";
    $candidate->Status = "Manager";
    $candidate->UserName = "Captain";
    
    $customField = new stdClass();
    $customField->FieldName = "custom field phone";
    $customField->Values = array("(123) 333-5555");
    
    $candidate->CustomFields = array($customField);
    
    $container = new stdClass();
    $container->Session = $pcrSession;
    $container->Candidate = $candidate;
    
    $client = get_soap_client();
    
    try{
        $result = $client->CreateCandidate($container);
        //print_r($result);
        return $result->CreateCandidateResult->RecordId;
    }catch(Exception $e){
        echo "Fault: ".$e->getMessage() . "\n";
    }
    //echo "REQUEST:\n" . htmlentities($client->__getLastRequest()) . "\n";
    //echo "RESPONSE: " . htmlentities($client->__getLastResponse()) . "<br />";  
}

function create_resume(){
    $pcrSession = get_pcr_session_obj();
    
    $resume = new stdClass();
    $resume->CandidateId = 173485340946662;
    
    $file = new StdClass();
    $file->Name = "cpicard.htm";
    $file->Bytes = "<html><body>Captain, USS Enterprise</body></html>";
    
    $resume->File = $file;
    
    $container = new stdClass();
    $container->session = $pcrSession;
    $container->Resume = $resume;
    
    $client = get_soap_client();
    
    try{
        $result = $client->CreateResume($container);
        //print_r($result);
        return $result->CreateResumeResult;
    }catch(Exception $e){
        echo "Fault: ".$e->getMessage() ."\n";
    }
    
    //echo "REQUEST:\n" . htmlentities($client->__getLastRequest()) . "\n";
    //echo "RESPONSE: " . htmlentities($client->__getLastResponse()) . "<br />";
}

function create_candidate_attachment(){
    $pcrSession = get_pcr_session_obj();
    
    $attachment = new stdClass();
    $attachment->CandidateId = 173485340946662;
    
    $file = new StdClass();
    $file->Name = "captains.log";
    $file->Bytes = "Stardate 20121212: Nothing happened today";
    
    $attachment->File = $file;
    
    $container = new stdClass();
    $container->Session = $pcrSession;
    $container->Attachment = $attachment;
    
    $client = get_soap_client();
    
    try{
        $result = $client->CreateCandidateAttachment($container);
        //print_r($result);
        return $result->CreateCandidateAttachmentResult->RecordId;
    }catch(Exception $e){
        echo "Fault: ".$e->getMessage() ."\n";
    }
    
    //echo "REQUEST:\n" . htmlentities($client->__getLastRequest()) . "\n";
    //echo "RESPONSE: " . htmlentities($client->__getLastResponse()) . "<br />";
}

//************* Get Functions ********************************** //
function get_pcr_session_obj(){
    global $pcrId;
    $pcrSession = new stdClass();
    $pcrSession->ApiKey = "ad7008f4c8650b25bba3440ef2e8ff4c";
    $pcrSession->AppId = "6d168d38";
    $pcrSession->Language = "en-US";
    $pcrSession->SessionId = $pcrId;
    return $pcrSession;
}

function get_default_company(){
    $pcrSession = get_pcr_session_obj();
    
    $companySearch = new stdClass();
    
    $expression1 = new stdClass();
    $expression1->Field = "CompanyType";
    $expression1->Operator = "Equal";
    $expression1->Values = array("DefaultCompany");
    
    $companySearch->SearchParams = array($expression1);
    
    $container = new stdClass();
    $container->Session = $pcrSession;
    $container->CompanySearch = $companySearch;
    
    $client = get_soap_client();
    
    try{
        $results = $client->SearchCompany($container);
        if($results->SearchCompanyResult->TotalRecords > 0){
            $company = $results->SearchCompanyResult->Results->Company;
            if(!is_array($company)){
                return $company->CompanyId;
            }else{
                return $company[0]->CompanyId;
            } 
        }        
    }catch(Exception $e){
        echo "Fault: ".$e->getMessage() . "\n";
    }
    //echo "REQUEST:\n" . htmlentities($client->__getLastRequest()) . "\n";
    //echo "RESPONSE: " . htmlentities($client->__getLastResponse()) . "<br />"; 
}

function get_candidate(){
    $pcrSession = get_pcr_session_obj();
    
    //$pcrCandId = $_REQUEST['pcrCandId'];
    $pcrCandId = 180271459537358;
    
    $candRequest = new stdClass();
    $candRequest->RecordId = $pcrCandId;
    $candRequest->IncludeJobDescription = true;
    $customFields = array(
        'custom field phone',
        'custom currency field',            
    );
    $candRequest->CustomFields = $customFields;
    
    $container = new stdClass();
    $container->Session = $pcrSession;
    $container->CandidateRequest = $candRequest;
    
    $client = get_soap_client();
    
    try{
        $results = $client->GetCandidate($container);
        $record = $results->GetCandidateResult;
        
        echo "CandidateId: ".$record->CandidateId . 
              " CompanyId: ".$record->CompanyId .
              " FirstName: ".$record->FirstName .
              " LastName: ".$record->LastName .
              " EmailAddress: ".$record->EmailAddress .
              " City: ".$record->City .
              " State: ".$record->State .
              " Country: ".$record->Country .
              " Address: ".$record->Address .
              " Zip: ".$record->Zip .
              " DesiredSalary: ". (isset($record->DesiredSalary->Value)? $record->DesiredSalary->Value." ".$record->DesiredSalary->CurrencyCode : "") .
              " DateEntered: ".$record->DateEntered;
              
        //print_r($record);
        if(isset($record->CustomFields->CustomField)){            
            $customfields = $record->CustomFields->CustomField;
            if(!is_array($customFields)) $customfields = array($customFields);
            foreach($customfields as $cfield){
                echo " CustomField.".$cfield->FieldName.": ";
                foreach($cfield->Values as $value){
                    echo $value.",";
                } 
            }
        }
        echo "<br />";
    }catch(Exception $e){
        echo "Fault: ".$e->getMessage() . "\n";
    }
    //echo "REQUEST:\n" . htmlentities($client->__getLastRequest()) . "\n";
    //echo "RESPONSE: " . htmlentities($client->__getLastResponse()) . "<br />";    
}

function get_position(){
    $pcrSession = get_pcr_session_obj();
    
    //$pcrJobId = $_REQUEST['pcrJobId'];
    $pcrJobId = 532299330763318;
    
    $posRequest = new stdClass();
    $posRequest->RecordId = $pcrJobId;
    $posRequest->IncludeJobDescription = true;
    $customFields = array(
        'custom_number',
        'Custom_Date',            
    );
    $posRequest->CustomFields = $customFields;
    
    $container = new stdClass();
    $container->Session = $pcrSession;
    $container->PositionRequest = $posRequest;
    
    $client = get_soap_client(); 
    try{
        $results = $client->GetPosition($container);
        $record = $results->GetPositionResult;
        
        echo "JobId: ".$record->JobId . 
              " ContactId: ".$record->ContactId .
              " CompanyId: ".$record->CompanyId .
              " JobTitle: ".$record->JobTitle .
              " JobType: ".$record->JobType .
              " ContactName: ".$record->ContactName .
              " ContactPhone: ".$record->ContactPhone .
              " ContactEmail: ".$record->ContactEmailAddress .
              " DegreeRequired: ".$record->DegreeRequired .
              " MinSalary: ".(isset($record->MinSalary->Value)? $record->MinSalary->Value." ".$record->MinSalary->CurrencyCode : "") .
              " MaxSalary: ".(isset($record->MaxSalary->Value)? $record->MaxSalary->Value." ".$record->MaxSalary->CurrencyCode : "") .
              " City: ".$record->City .
              " State: ".$record->State .
              " Country: ".$record->Country .
              " Zip: ".$record->Zip .
              " CompanyName: ".$record->CompanyName .
              " DatePosted: ".$record->DatePosted;
        //print_r($record);
        if(isset($record->CustomFields->CustomField)){            
            $customfields = $record->CustomFields->CustomField;
            if(!is_array($customFields)) $customfields = array($customFields);
            foreach($customfields as $cfield){
                echo " CustomField.".$cfield->FieldName.": ";
                foreach($cfield->Values as $value){
                    echo $value.",";
                } 
            }
        }
        echo "<br />";
    }catch(Exception $e){
        echo "Fault: ".$e->getMessage() . "\n";
    }
    //echo "REQUEST:\n" . htmlentities($client->__getLastRequest()) . "\n";
    //echo "RESPONSE: " . htmlentities($client->__getLastResponse()) . "<br />";   
}

//*************  Search Functions ****************************** //
function get_job_search()
{
    //$html_contents = file_get_contents("wp-content/plugins/PCRecruiterJobBoard/htmlTemplates/searchTemplate.html");
    $html_contents = file_get_contents("htmlTemplates/searchTemplate.html");
    return $html_contents;
}

function search_candidate(){
    //$keywords = $_POST['pcrSearchKeywords'];
    $keywords = "";
    
    $candSearch = new stdClass();
    $candSearch->Page = '1';
    $candSearch->ResultsPerPage = '20';
    $candSearch->Keywords=$keywords;
    
    $customFields = array(
        'custom field phone',
        'custom currency field',           
    );
    
    $candSearch->CustomFields = $customFields;
    
    $expression1 = new stdClass();
    $expression1->Field = "EmailAddress";
    $expression1->Operator = "Equal";
    $expression1->Values = array("christest@mainsequence.net");
    
    $candSearch->SearchParams = array($expression1);
    
    $pcrSession = get_pcr_session_obj();
                
    $container = new stdClass();
    $container->Session = $pcrSession;
    $container->CandidateSearch = $candSearch;
    
    $client = get_soap_client(); 
    try{
        $results = $client->SearchCandidate($container);
        echo "totalcount: \n";
        echo $results->SearchCandidateResult->TotalRecords;
        echo "<br />";
        
        $candidate = $results->SearchCandidateResult->Results->Candidate;
        if(!is_array($candidate)) $candidate = array($candidate);
        
        foreach($candidate as $record){
            echo "CandidateId: ".$record->CandidateId . 
              " CompanyId: ".$record->CompanyId .
              " FirstName: ".$record->FirstName .
              " LastName: ".$record->LastName .
              " EmailAddress: ".$record->EmailAddress .
              " City: ".$record->City .
              " State: ".$record->State .
              " Country: ".$record->Country .
              " Zip: ".$record->Zip .
              " CurrentSalary: ". (isset($record->CurrentSalary->Value)? $record->CurrentSalary->Value." ".$record->CurrentSalary->CurrencyCode : "") .
              " DateEntered: ".$record->DateEntered;
            if(isset($record->CustomFields->CustomField)){
                $customfields = $record->CustomFields->CustomField;
                if(!is_array($customFields)) $customfields = array($customFields);
                foreach($customfields as $cfield){
                    echo " CustomField.".$cfield->FieldName.": ";
                    foreach($cfield->Values as $value){
                        echo $value.",";
                    } 
                }
            }
            echo "<br />";
        }
    }catch(Exception $e){
        echo "Fault: ".$e->getMessage() . "\n";
    }   
    
    //echo "REQUEST:\n" . htmlentities($client->__getLastRequest()) . "<br />";
    //echo "RESPONSE: " . htmlentities($client->__getLastResponse()) . "<br />";
}

function search_job()
{
    $index = 0;
    $pageSize  =20;
    //$keywords = $_POST['pcrSearchKeywords'];
    $keywords = "software";
    $query = "";
    //$searchJobTitle = $_POST['pcrSearchJobTitle'];
    //$searchCity = $_POST['pcrSearchCity'];
    //$searchState = $_POST['pcrSearchState'];
    //$searchCountry = $_POST['pcrSearchCountry'];
    //$searchEmail = $_POST['pcrSearchEmail'];
    
    $posSearch = new stdClass();
    $posSearch->Page = '1';
    $posSearch->ResultsPerPage = '20';
    $posSearch->Keywords=$keywords;
    
    $customFields = array(
        'custom_number',
        'Custom_Date',            
    );
    
    $posSearch->CustomFields = $customFields;
    
    $posExpression1 = new stdClass();
    $posExpression1->Field = "JobTitle";
    $posExpression1->Operator = "Equal";
    $posExpression1->Values = array("software engineer 1");
    
    $posExpression2 = new stdClass();
    $posExpression2->Field = "MaxSalary";
    $posExpression2->Operator = "GreaterThanOrEqual";
    $posExpression2->Values = array("10000");
    
    $posSearch->SearchParams = array($posExpression1,$posExpression2);
    
    $pcrSession = get_pcr_session_obj();
                
    $container = new stdClass();
    $container->Session = $pcrSession;
    $container->PositionSearch = $posSearch;
    
    $client = get_soap_client(); 
    try{
        $results = $client->SearchPosition($container);
        echo "totalcount: \n";
        echo $results->SearchPositionResult->TotalRecords;
        echo "<br />";
        
        $positions = $results->SearchPositionResult->Results->Position;
        if(!is_array($positions)) $positions = array($positions);
        
        foreach($positions as $record){
            echo "JobId: ".$record->JobId . 
                  " ContactId: ".$record->ContactId .
                  " CompanyId: ".$record->CompanyId .
                  " JobTitle: ".$record->JobTitle .
                  " JobType: ".$record->JobType .
                  " ContactName: ".$record->ContactName .
                  " ContactPhone: ".$record->ContactPhone .
                  " ContactEmail: ".$record->ContactEmail .
                  " DegreeRequired: ".$record->DegreeRequired .
                  " MinSalary: ".$record->MinSalary .
                  " MaxSalary: ".$record->MaxSalary .
                  " City: ".$record->City .
                  " State: ".$record->State .
                  " Country: ".$record->Country .
                  " Zip: ".$record->Zip .
                  " CompanyName: ".$record->CompanyName .
                  " DatePosted: ".$record->DatePosted;
            if(isset($record->CustomFields->CustomField)){
                $customfields = $record->CustomFields->CustomField;
                if(!is_array($customFields)) $customfields = array($customFields);
                foreach($customfields as $cfield){
                    echo " CustomField.".$cfield->FieldName.": ";
                    foreach($cfield->Values as $value){
                        echo $value.",";
                    } 
                }
            }
            echo "<br />";
        }
    }catch(Exception $e){
        echo "Fault: ".$e->getMessage() . "\n";
    }   
    
    //echo "REQUEST:\n" . htmlentities($client->__getLastRequest()) . "<br />";
    //echo "RESPONSE: " . htmlentities($client->__getLastResponse()) . "<br />";
    
}
?>
