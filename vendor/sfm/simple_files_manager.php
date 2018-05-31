<?php
/**
 * A simple library to manage files with PHP.
 *
 * @author  Víctor Lázaro <www.vlazaro.es>
 * @version 0.1
 * @license https://opensource.org/licenses/MIT
 * @link    https://github.com/vlazaroes/simple-files-manager
 */
class SFM {
	private static $file;

	public function __construct() {
		static::$file = array();
	}

	/**
	 * Upload a file to a specific directory.
	 * 
	 * @param string $input		  Name of the form file input.
	 * @param string $directory   Destination directory where to save the file.
	 * 
	 * @uses check_name_exists
	 * 
	 * @return array $response    The response with the result of the method.
	 */
	public static function upload_file($input, $directory) {
		static::$file = array(
			'name' 	   => $_FILES[$input]['name'],
			'tmp_name' => $_FILES[$input]['tmp_name'],
			'type' 	   => $_FILES[$input]['type'],
			'size' 	   => $_FILES[$input]['size'],
			'error'    => $_FILES[$input]['error']
		);
		
		$response = [
			'status' => 'error',
			'code'   => '409'
		];

		if(is_uploaded_file(static::$file['tmp_name'])) {
			if(!is_dir($directory)) {
				if(!mkdir($directory, 0777, true)) {
					$response['message'] = 'The destination directory couldn\'t be created.';
				}
			} else {
				static::$file['name'] = static::check_name_exists(static::$file['name'], $directory);

				$file_upload = move_uploaded_file(static::$file["tmp_name"], $directory . "/" . static::$file["name"]);
				if($file_upload) {
					$response = [
						'status'  	=> 'success',
						'code' 	  	=> '200',
						'message' 	=> 'The file has been upload successfully.',
						'file_info' => static::$file
					];					
				} else {
					$response['message'] = 'The file couldn\'t be uploaded.';
				}
			}
		} else {
			$response['message'] = 'The file hasn\'t been uploaded by HTTP POST.';
		}
		
		return $response;
	}

	/**
	 * Get the information from a specified file.
	 * 
	 * @param string $file_name   Name of the file from which to obtain the information.
	 * @param string $directory   Source directory where the file is found.
	 * 
	 * @return array $response    The information of the specified file.
	 */
	public static function get_file_info($file_name, $directory) {
		$current_files = scandir($directory);
		if(in_array($file_name, $current_files) != 0) {
			$response = array(
				'name' 		  => $file_name,
				'size' 		  => filesize($directory . '/' . $file_name),
				'upload_date' => filemtime($directory . '/' . $file_name)
			);
		} else {
			$response = array(
				'status'  => 'error',
				'code' 	  => '404',
				'message' => 'The specified file wasn\'t found'
			);
		}
		
		return $response;
	}

	/**
	 * Rename a file from a specific directory.
	 * 
	 * @param string $old_name    Name of the file to be renamed.
	 * @param string $new_name    New name for the specified file.
	 * @param string $directory   Source directory where the file is found.
	 * 
	 * @uses check_name_exists
	 * 
	 * @return array $response    The response with the result of the method.
	 */
	public static function rename_file($old_name, $new_name, $directory) {
		$file_type = explode('.', $old_name);	
		$new_name  = static::check_name_exists(($new_name . '.' . $file_type[count($file_type) - 1]), $directory);

		if(rename($directory . '/' . $old_name, $directory . '/' . $new_name)) {
			$response = [
				'status'  => 'success',
				'code' 	  => '200',
				'message' => 'The file has been renamed successfully.'
			];
		} else {
			$response = [
				'status'  => 'error',
				'code'    => '409',
				'message' => 'The file couldn\'t be renamed.'
			];
		}

		return $response;
	}

	/**
	 * Delete a file from a specified directory.
	 * 
	 * @param string $file_name   Name of the file to be deleted.
	 * @param string $directory   Source directory where the file is found.
	 * 
	 * @return array $response    The response with the result of the method.
	 */
	public static function delete_file($file_name, $directory) {
		if(unlink($directory . '/' . $file_name)) {
			$response = [
				'status'  => 'success',
				'code' 	  => '200',
				'message' => 'The file has been removed successfully.'
			];
		} else {
			$response = [
				'status'  => 'error',
				'code'    => '409',
				'message' => 'The file could not be deleted.'
			];
		}

		return $response;
	}

	/**
	 * Check if the file already exists in the source directory and rename it.
	 * 
	 * @param string $file_name    Name of the file to check.
	 * @param string $directory    Source directory where the file is found.
	 * 
	 * @return string $file_name   The name of the file allowed.
	 */
	private static function check_name_exists($file_name, $directory) {
		$file_cut   = explode('.', $file_name);			
		$file_count = 1;
		
		$current_files = scandir($directory);
		while(in_array($file_name, $current_files) != 0) {
			$file_name = $file_cut[0] . ' (' . $file_count . ').' . $file_cut[count($file_cut) - 1];
			$file_count++;
		}

		return $file_name;
	}
}
 ?>