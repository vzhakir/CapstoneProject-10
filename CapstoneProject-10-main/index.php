<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>ISNIP Bowtie Pipeline</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white min-h-screen p-6">

  <div class="max-w-xl mx-auto bg-gray-800 p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-4">📥 Upload FASTQ File to Run Bowtie</h1>

    <?php if (isset($_GET['status'])): ?>
      <?php if ($_GET['status'] === 'success'): ?>
        <p class="text-green-400 mb-4">✅ File uploaded & alignment completed!</p>
        <p><a class="text-blue-400 underline" href="download.php?file=output.sam">Download output.sam</a></p>
      <?php elseif ($_GET['status'] === 'error'): ?>
        <p class="text-red-400 mb-4">❌ Upload failed or Bowtie error.</p>
      <?php endif; ?>
    <?php endif; ?>

    <form action="upload.php" method="post" enctype="multipart/form-data" class="space-y-4">
      <div>
        <label class="block mb-1">Select FASTQ file:</label>
        <input type="file" name="fastqfile" accept=".fastq" required class="w-full text-gray-900 p-2 rounded bg-white" />
      </div>
      <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
        Run Bowtie
      </button>
    </form>
  </div>

</body>
</html>
