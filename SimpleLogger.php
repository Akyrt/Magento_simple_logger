<?php

// Magento simple logger

// 1. Skrypt umieścić w katalogu głównym projektu

// 2. Na początku każdego pliku zaincludować skrypt include( $_SERVER['DOCUMENT_ROOT'] .'/SimpleLogger.php');

// 3. Umieścić wywołanie loggera w miejscu gdzie chcemy aby był uruchamiany oraz podać mu dowolne parametry w tablicy parametrów log_msgs_array

      //  // ****************************** WYWOLANIE LOGGERA ******************************
      //  $log_msg1 = "Wywołanie loggera";
      //  $log_msg2 = "OK";
      //  $log_msgs_array = array($log_msg1, $log_msg2);
      //  $fileDirName = dirname(__FILE__);
      //  $fileName = basename(__FILE__);
      //  // generacja loga
      //  wh_log($log_msgs_array, $fileDirName, $fileName);
      //  // ****************************** KONIEC WYWOLANIA LOGGERA ******************************


// ****************************** LOGGER ******************************
/**
 * @param array $log_msg - tablica wiadomości loggera
 * @param $fileDirName - ścieżka do pliku w którym jest logger wywołany
 * @param $fileName - nazwa pliku w którym jest logger wywołany
 */
if(!function_exists("wh_log")){
    function wh_log($log_msg, $fileDirName, $fileName)
    {

        $requestUri = Mage::app()->getRequest()->getRequestUri();
        $moduleName = Mage::app()->getRequest()->getModuleName();
        $controllerName = Mage::app()->getRequest()->getControllerName();
        $actionName = Mage::app()->getRequest()->getActionName();
        $routeName = Mage::app()->getRequest()->getRouteName();
        // pobranie aktualnej daty
        $log_time = date('Y-m-d h:m:s');

        $baseDir = Mage::getBaseDir();

        // sprawdzenie czy katalog log istnieje, jeżeli nie to utworzenie
        $log_filename = $baseDir . "/log";
        if (!file_exists($log_filename)) {
            // create directory/folder uploads.
            mkdir($log_filename, 0777, true);
        }


        $log_file_data = $log_filename . '/usage_log_of_module_' . $moduleName . '.log';

        // wczytanie zawartości pliku do arraya
        $fh = file($log_file_data);

        try {
            $logNumber = intval($fh[1]);
        } catch (Exception $exception) {
            $logNumber = 0;
            echo $exception;
            errors_log(array($exception->getMessage()));
        }

        $logNumber++;
        $linesCount = count($fh);

        $fh = messageContent($log_msg, $logNumber, $fh, $linesCount, $log_time, $routeName, $fileName, $fileDirName, $requestUri, $moduleName, $controllerName, $actionName);

        // zapisanie pliku
        file_put_contents($log_file_data, implode('', $fh));

    }
}
if(!function_exists("errors_log")){
    function errors_log($log_msg)
    {
        // pobranie aktualnej daty
        $log_time = date('Y-m-d h:m:s');
        $baseDir = Mage::getBaseDir();
        $fileName = basename(__FILE__);
        $fileDirName = dirname(__FILE__);
        $requestUri = Mage::app()->getRequest()->getRequestUri();
        $moduleName = Mage::app()->getRequest()->getModuleName();
        $controllerName = Mage::app()->getRequest()->getControllerName();
        $actionName = Mage::app()->getRequest()->getActionName();
        $routeName = Mage::app()->getRequest()->getRouteName();

        // sprawdzenie czy katalog log istnieje, jeżeli nie to utworzenie
        $log_filename = $baseDir . "/logger_errors";
        if (!file_exists($log_filename)) {
            // create directory/folder uploads.
            mkdir($log_filename, 0777, true);
        }

        // wczytanie zawartości pliku do arraya
        $fh = file($baseDir . '/logger_errors/logger_errors.log');

        try {
            $logNumber = intval($fh[1]);
        } catch (Exception $exception) {
            $logNumber = 0;
            echo $exception;
        }

        $logNumber++;
        $linesCount = count($fh);

        $fh = messageContent($log_msg, $logNumber, $fh, $linesCount, $log_time, $routeName, $fileName, $fileDirName, $requestUri, $moduleName, $controllerName, $actionName);

        // zapisanie pliku
        $log_file_data = $log_filename . '/logger_errors.log';
        file_put_contents($log_file_data, implode('', $fh));
    }
}
/**
 * @param $log_msg
 * @param $logNumber
 * @param array|boolean $fh
 * @param $linesCount
 * @param $log_time
 * @param $routeName
 * @param $fileName
 * @param $fileDirName
 * @param $requestUri
 * @param $moduleName
 * @param $controllerName
 * @param $actionName
 * @return array
 */
if(!function_exists("messageContent")){
    function messageContent($log_msg, $logNumber, $fh, $linesCount, $log_time, $routeName, $fileName, $fileDirName, $requestUri, $moduleName, $controllerName, $actionName)
    {
        $fh[0] = 'Logs count: ' . "\n";
        $fh[1] = $logNumber . "\n";    ++$linesCount;
        $fh[$linesCount] = "************** Start Log For Day : '" . $log_time . "'**********" . "\n";
        for ($i = 0; $i < count($log_msg); $i++) {
            ++$linesCount;
            $fh[$linesCount] = "Message number " . $i . ": " . $log_msg[$i] . "\n";
        }
        ++$linesCount;
        $fh[$linesCount] = "Route name: " . $routeName . "\n";
        ++$linesCount;
        $fh[$linesCount] = "File name: " . $fileName . "\n";
        ++$linesCount;
        $fh[$linesCount] = "File dir name: " . $fileDirName . "\n";
        ++$linesCount;
        $fh[$linesCount] = "RequestUri: " . $requestUri . "\n";
        ++$linesCount;
        $fh[$linesCount] = "Module name: " . $moduleName . "\n";
        ++$linesCount;
        $fh[$linesCount] = "Controller name: " . $controllerName . "\n";
        ++$linesCount;
        $fh[$linesCount] = "Action name: " . $actionName . "\n";
        ++$linesCount;
        $fh[$linesCount] = "************** END Log For Day : '" . $log_time . "'**********" . "\n";
        return $fh;
    }
}
// ****************************** KONIEC LOGGERA ******************************

?>
