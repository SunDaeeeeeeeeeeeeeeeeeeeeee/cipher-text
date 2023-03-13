<?php
// Check if a file has been uploaded
if(isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
    $filename = $_FILES['file']['name'];
    $tempFilePath = $_FILES['file']['tmp_name'];
    $fileExtension = pathinfo($filename, PATHINFO_EXTENSION);

    // Check if the uploaded file is a text file
    if($fileExtension == 'txt') {
        // Read the contents of the uploaded file
        $input = file_get_contents($tempFilePath);

        // Encrypt the contents using a shift of 3
        $cipher = new CaesarCipher(3);
        $encrypted = $cipher->encrypt($input);

        // Write the encrypted text to a new file
        $outputFile = 'encrypted.txt';
        file_put_contents($outputFile, $encrypted);

        // Download the encrypted file
        header('Content-Disposition: attachment; filename="' . $outputFile . '"');
        header('Content-Type: text/plain');
        readfile($outputFile);

        // Delete the output file from the server
        unlink($outputFile);
    } else {
        echo "Error: Only text files (.txt) are allowed.";
    }
} else {
    echo "Error: No file uploaded or an error occurred.";
}

class CaesarCipher {
    public $shift;
    const alphabet = array(
        "lowercase" => array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z"),
        "uppercase" => array("A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z")
    );
    public function __construct($shift = 0) {
        $this->shift = $shift % 26;
    }
    public function encrypt($input) {
        $result = str_split($input);
        for ($i = 0; $i < count($result); $i++) {
            for ($j = 0; $j < 26; $j++) {
                if ($result[$i] === CaesarCipher::alphabet["lowercase"][$j]) {
                    $result[$i] = CaesarCipher::alphabet["lowercase"][($j + $this->shift) % 26];
                    $j = 26;
                } elseif ($result[$i] === CaesarCipher::alphabet["uppercase"][$j]) {
                    $result[$i] = CaesarCipher::alphabet["uppercase"][($j + $this->shift) % 26];
                    $j = 26;
                }
            }
        }
        $result = implode($result);
        return $result;
    }
}

// Read the contents of the sample file
$input = file_get_contents($filename);

// Encrypt the contents using a shift of 3
$cipher = new CaesarCipher(3);
$encrypted = $cipher->encrypt($input);

// Write the encrypted text to a new file
file_put_contents('encrypted.txt', $encrypted);
?>
