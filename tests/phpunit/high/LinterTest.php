<?php

/**
 * Perform linter tests on this repo
 */
class LinterTest extends TestCase
{
  /**
   * Folders containing php files for linting checks
   * comments are the reason they where not able to be tested at the time
   *
   * @var array
   */
  protected $phpFolders = [

    'app',
    'config',
    'database',
    'resources',
  ];

  /**
   * Create a list of php files
   *
   * @return array
   */
  public function testGetPhpFiles()
  {
    // Files arrays
    $files = [];
    $phpFiles = [];

    // For every specified folder
    foreach ($this->phpFolders as $folder) {

      // Get files from directory
      $files = array_merge($files, FileHelper::directoryFiles($folder));
    }

    // Select php only files
    foreach ($files as $file) {
      if (substr($file, -4) === '.php') {
        $phpFiles[] = $file;
      }
    }

    // Check that we have at least 10 php files across all collected
    $count = count($phpFiles);
    $this->assertTrue($count > 10, json_encode([
      'count' => $count,
      'expected' => 'total number of php files must exceed 10 files',
    ], JSON_UNESCAPED_SLASHES));

    // Return end point data
    return $phpFiles;
  }

  /**
   * Make sure specified php files are passing lint tests
   *
   * @depends testGetPhpFiles
   * @return void
  **/
  public function testPhpLinter(Array $files)
  {
    // For every file
    foreach ($files as $file) {

      // status output
      fwrite(STDOUT, "{$file}\n");

      // Execute linter
      $result = system("php -l {$file} 2>&1", $returnCode);

      // Set return code to boolean
      $returnCode = (bool) $returnCode;

      // Check for passed lint test
      $this->assertTrue($returnCode === false, json_encode([
        'file' => $file,
        'result' => $result,
      ], JSON_UNESCAPED_SLASHES));
    }
  }
}
