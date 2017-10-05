<?php
//code for conversion of various file fomats to PDF

	
	global $dPath, $docClass, $dPathclone;

	//for wordDoc documents and other text Applications.
	$fileParts = explode(".", $dPath);
	$Exts = end($fileParts);
	$Exts = strtolower($Exts);
	
	$mumPath = str_replace("/","\\", $dPath);
	
	$fileckr = str_replace("/","\\", $dPath);
	$fileckr = str_replace($Exts, 'pdf', $fileckr);
	$fileckr = str_replace('..', '.', $fileckr);
	
/**--------------------------------------------------------------------------------------------------------------------
CONVERSION ROUTINE USING UNIVERSAL DOCUMENT CONVERTER (UDC)
-----------------------------------------------------------------------------------------------------------------------**/
if(($Exts == "doc" || $Exts == "docx") && !file_exists($fileckr)){
	//Creating Universal Document Converter object 
		
	 $objUDC = new COM("UDC.APIWrapper");
		
	  //Setting up Universal Document Converter 
			
	 $itfPrinter = $objUDC->Printers("Universal Document Converter");
			
	 $itfProfile = $itfPrinter->Profile;
			
	 $itfProfile->PageSetup->ResolutionX = 300;
	 $itfProfile->PageSetup->ResolutionY = 300;
	 $itfProfile->PageSetup->Orientation = 0;
		
	 $itfProfile->FileFormat->ActualFormat = 7; //PDF 
	 $itfProfile->FileFormat->PDF->Multipage = 2; //Multipage mode
		
	 $itfProfile->OutputLocation->Mode = 1;
	  
	 $dPathclone = $dPath;
	 $lowPath = str_replace("/","\\", $dPath);
	 $lowPathparts= explode('\\', $lowPath );
	 $clippedLowPath = $lowPathparts[0].'\\'.$lowPathparts[1];
	  
	 $fpath = $itfProfile->OutputLocation->FolderPath = 'C:\xampp\htdocs\www.example.com\\'.$clippedLowPath; //'&[Documents]\UDC Output Files\\';
	 $fname = $itfProfile->OutputLocation->FileName = '&[DocName(0)].&[ImageType]';
	  
	 $itfProfile->OutputLocation->OverwriteExistingFile = 1;

	 $itfProfile->PostProcessing->Mode = 0;
			
	  //Create MS Word object
	 $file = 'C:\xampp\htdocs\www.example.com\\'. $lowPath;
		
	 $WordApp = new COM("Word.Application");
		
	  //Print the document 
		
	 $WordDoc = $WordApp->Documents->Open($file,0,1);
	 $WordApp->ActivePrinter = "Universal Document Converter";
	 $WordApp->PrintOut(False);
		
	  //Close the document and MS Word
		
	 $WordDoc->Close();
	 $WordApp->Quit();

	 $dPath = str_replace($Exts, 'pdf', $lowPath);
	 //setting up file path for access over network
	 $dPath = str_replace('\\', '/', $dPath);
	 $dPath = str_replace('..', '.', $dPath);
	 
	 //time gap for created document to be ready for modification.
	 sleep(3);
	}
	else{
		//setting file path for original document
		$dPathclone = $dPath;
	}

/**----------------------------------------------------------------------------------------------------------------------
Adding watermark to created or existing PDF files Using FPDF
------------------------------------------------------------------------------------------------------------------------**/
//preping for modification PDF files.
define('FPDF_FONTPATH', 'font/');
require('rotation.php');
require_once('fpdf/fpdf.php');
require_once('fpdi/fpdi.php');

//If original or modified file now exist as a PDF document.
if(file_exists($fileckr)){
	//Altering working file path
	$dPath = $fileckr;

	//setting up file path for file access over network
	$dPath = str_replace('\\', '/', $dPath);
	$dPath = str_replace('..', '.', $dPath);
	
	//specifying, specifically, the file extension of current working file.
	global $Exts;
	$Exts = 'pdf';
	
	class PDF extends PDF_Rotate
	{
		function Header()
		{
			global $docClass;
			//Put watermark
			$this->SetFont('Arial', 'B', 50);
			$this->SetTextColor(255, 192, 203);
			$this->RotatedText(70, 170, $docClass, 45);//PLACEHOLDER:'W a t e r m a r k   d e m o'
		}

		function RotatedText($x, $y, $txt, $angle)
		{
			//Text rotated around its origin
			$this->Rotate($angle, $x, $y);
			$this->Text($x, $y, $txt);
			$this->Rotate(0);
		}
	}

	$pdf = new PDF();
	$pdf->Open();
	$pdf->SetFont('Arial', '', 12);
		
	//Setting the source PDF file
	$pagecount = $pdf->setSourceFile($dPath);

	//Importing the page(s) of the file
	for($i = 1; $i <= $pagecount; $i++){
		//$pdf->AddPage();
		$pdfTpl = $pdf->importPage($i);
		
		//checking for PDF document layout
		$docspecs = $pdf->getTemplateSize($pdfTpl);
		$pdf->AddPage($docspecs['h'] > $docspecs['w'] ? "P" : "L");
		
		//Use this page as template
		$pdf->useTemplate($pdfTpl);
	}	

	//Print watermark
	$pdf->Output(str_replace('.pdf', '(Modified).pdf', $dPath), "F");
	$dPath = str_replace('.pdf', '(Modified).pdf', $dPath);
	//echo $dPath;
}
else{
	$dPath = $mumPath;
	
	//setting up file path for access over network
	$dPath = str_replace('\\', '/', $dPath);
	$dPath = str_replace('..', '.', $dPath);
	
	//setting file path for original document
	$dPathclone = $dPath;
}
?>