<?php
    set_time_limit(0);

    spl_autoload_register(function ($class_name) {
        include $class_name . '.php';
    });
    
    $break_line         = "<br>";
    
    //var_dump($query_string); exit;
    define('BREAK_LINE', $break_line);
    
    $values = file("pmc_result.txt");     
    //var_dump(    $values);exit;
    try {
        
    
        $file_name = "US National Library of Medicine National Institutes of Health ";
        
        $file           = Util::slug(trim($file_name)) . ".bib";
        $user_agent     = (!empty($_SERVER["HTTP_USER_AGENT"])) ? $_SERVER["HTTP_USER_AGENT"] : "Mozilla/5.0 (Macintosh; Intel Mac OS X 10.13; rv:58.0) Gecko/20100101 Firefox/58.0";
        define('USER_AGENT', $user_agent);   
        define('COOKIE', "ncbi_sid=CE8DD94BD2372381_0780SID; _ga=GA1.2.4633984.1562604090; _gid=GA1.2.2058094878.1562604090; pmc.article.report=; WebEnv=1DY2fRXysAUulP34500qW8y_Ptum9Rbas70YsMDn2zFR8w_9huHQiSSqm7TWWkk2n0s0yrVZh-o3Ilb3k3JsS_42YDQQR85kt38kj%40CE8DD94BD2372381_0780SID; ncbi_pinger=N4IgDgTgpgbg+mAFgSwCYgFwgMIFEAc+ADACIBspATAMxkBiArNgIxFvvvMCCJbuuAOgC2cZmRAAaEAGMANsmkBrAHZQAHgBdMoIphnINAQw3IA9qrVhTEDVAgBaAGanTtiBICuye8ukAjZCswMDt7aXNbZQ1Pb0QoWRCHaABnKGivH39A02DQgHcIQ1yIAAIM3wCgxPs/Q2SFCQBzCDRJEGY9cqz1N2VDWXllRXsGNuoATj0AdgZKZgZdKQAWXSwZykpx0eWlvTkFFR62pdGsSeXxLA0IDyhjyaxpA2MzCysbOwkW5LaFvUQNBowMkMAB6UFFZACCpQ5SyITQ5CIASNUwwUHyDSg6QaSygmDMUFgITSUEAfmcECExgAvN8AGRoGkMMhTKZLDn01CmPJw0yGVA0663X4dLC/Sh6ABKAEkAMogAC+Ug8fIFFi0GFA1Goekc/VSY12Vxudyk1HwejGDxAZCWzGom2Oq1t9qWU11O06atQGuOp1tv0uIEWIAYUz0obIYpAkuVMlMQiE5j9WtjeigUWgAC8xnpidJjnpudJkh4hL8rVJgyWyxWpBGsAChLI2pasJRQzbUFB9R5ZFopMwYx0h7qsH8h8aQKlDBBpIg2mI9P1W0PGyB9bJDUP25uDWbYy68IRSBQSDR6ExWBwONxeER+MJROIpHMM1moNmMAWMLXyxgAByADygG4G0Gx6Hk0HQlk0LwoiyKojAEE2mIbBjC64wYeaI4UKGjp6PgFDHOOIYCJQDACKOYZkehoYMNOUxEJaiqKkAA===");
        define('FILE', $file);
        define('FILE_LOG', Util::slug(trim($file_name)) . "_log.txt");

        $ris = "";
        foreach($values as $value)  {
            $id = trim(str_replace("PMC", "", $value));
            $url = "https://api.ncbi.nlm.nih.gov/lit/ctxp/v1/pmc/?format=ris&id=$id";                
            $ris .= Util::getURL($url, COOKIE, USER_AGENT);            
            //var_dump($url, $ris);exit;
            $sleep = rand(5,9);
            Util::showMessage("Wait for " . $sleep . " seconds before executing next page");
        }
        
        file_put_contents(FILE, $ris, FILE_APPEND);
        Util::showMessage("Saved file");

    } catch(Exception $e) {
        Util::showMessage($e->getMessage());
    }
?>
