<!DOCTYPE html>
<html>
<head><title>ISNiP Bowtie2 Upload</title></head>
<body>
<h1>Upload FASTQ File for Bowtie2 Alignment</h1>

<?php
$uploadDir = '/shared_data/';
$jobFile = $uploadDir . 'job.txt';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['fastqfile']) && $_FILES['fastqfile']['error'] == 0) {
        $filename = basename($_FILES['fastqfile']['name']);
        $dest = $uploadDir . $filename;

        if (move_uploaded_file($_FILES['fastqfile']['tmp_name'], $dest)) {
            echo "<p>File uploaded successfully! Starting alignment...</p>";

            // Prepare Bowtie2 command
            $indexBase = '/opt/bowtie-index/index';  // adjust if needed
            $inputFile = escapeshellarg($dest);
            $outputFile = escapeshellarg($uploadDir . 'output.sam');
            $logFile = escapeshellarg($uploadDir . 'bowtie2.log');

            // Run Bowtie2 and capture output and errors
            $cmd = "bowtie2 -x $indexBase -U $inputFile -S $outputFile 2> $logFile";
            exec($cmd, $output, $return_var);

            if ($return_var === 0) {
                echo "<p>Alignment completed successfully!</p>";
                echo "<p><a href='/download.php?file=output.sam'>Download output.sam</a></p>";
            } else {
                echo "<p style='color:red;'>Bowtie2 failed to run. Check logs:</p>";
                echo "<pre>" . htmlspecialchars(file_get_contents($uploadDir . 'bowtie2.log')) . "</pre>";
            }

        } else {
            echo "<p style='color:red;'>Failed to move uploaded file.</p>";
        }
    } else {
        echo "<p style='color:red;'>No file uploaded or upload error.</p>";
    }
} else {
    // Show status if output exists
    $outputSam = $uploadDir . 'output.sam';
    if (file_exists($outputSam)) {
        echo "<p>Previous alignment done! <a href='/download.php?file=output.sam'>Download output.sam</a></p>";
    }
}
?>

<form method="POST" enctype="multipart/form-data">
    <input type="file" name="fastqfile" accept=".fastq,.fq" required />
    <button type="submit">Upload & Run Alignment</button>
</form>
</body>
</html>

