<?php
/**
 * generates the classes from 'proto_def' to 'proto_gen'
 * @author Galen Lin
 */

require_once("parser/pb_parser.php");

function parseAll() {
	$in = "proto_def";
	$out = "proto_gen";
	$dir = dir($in);
	if (!is_dir($out)) {
		if (!mkdir($out)) {
			echo("Failed to create ouput directory.\n");
			return;
		}
	}

	$parser = new PBParser();
	while (($file = $dir->read()) != false) {
		$pathInfo = pathInfo($file);
		$ext = $pathInfo['extension'];
		if ($ext == 'proto') {
			// Parse '*.proto' files in `proto_def` directory.
			$inFile = $in . '/' . $file;
			$types = array('php', 'java', 'objc');
			echo("Parsing \"" . $file . "\"...\n");
			foreach ($types as $type) {
				$outDir = $out . '/' . $type;
				if (!is_dir($outDir)) {
					if (!mkdir($outDir)) {
						echo("[FAILED] (mkdir error)\n");
						return;
					}
				}

				echo "    - " . $type;
				if ($type === 'php') {
					// Generate source by `pb_parser`
					$parser->parse($inFile, $outDir);
				} else {
					// Generate source by `protoc`
					shell_exec('./gen.sh ' . $inFile . ' ' . $type . ' ' . $outDir);
				}
				
				echo("\t[  OK  ]\n");
			}
		}
	}
	$dir->close();
}

parseAll();

?>