<?php
/**------------------------------------------------------------------------------------------------------------------------
HANDLES ACTUAL ANONOTATION OF PDF DOCUMENTS
---------------------------------------------------------------------------------------------------------------------------**/
//include'/accessctrl.php';

require_once('fpdf/fpdf.php');
require_once('fpdi/fpdi.php');

//NOTE: Some variables are not local. This file is for inclusion in other files not a stand-alone file.
	global $docTitle, $extdAnnotatn;
	
	$extdAnnotatn = "";

//Extracting the latest Annotation on document.
	$query10 = sprintf("SELECT * FROM doc_annotations  WHERE doc_title ='%s' ORDER BY ID DESC",
						mysqli_real_escape_string($dblink, $docTitle)
					);

	$result10 = mysqli_query($dblink, $query10) or die("Error in query: $query10.".mysqli_error($dblink));
		
	$extdAnnotatn = mysqli_result($result10, 0,'annotation');
	
	if(mysqli_num_rows($result10) > 0){
	
		class MyPdf extends FPDI{
			function Header() {
				global $extdAnnotatn;
				$this->SetFont('Times','',16);
				$annotationWidth = $this->GetStringWidth($extdAnnotatn)+5;//+150;
				$this->SetDrawColor(255,0,0);
				$this->SetTextColor(255,0,0);
				$this->SetLineWidth(0.2);
				$this->SetX(120);
				$this->Cell($annotationWidth,9,$extdAnnotatn,1,1,'C');
				$this->Ln(10);
			}
		}
		
		
		$pdf = new MyPdf( );
		$pdf->Open();
		$pdf->SetFont('Arial','B',16);
		
		//Setting the source PDF file
		$pagecount = $pdf->setSourceFile($dPath);
		
		//Importing the other page(s) of the file
		for($i = 1; $i <= $pagecount; $i++){
			//$pdf->AddPage();
			$pdfTpl = $pdf->importPage($i);
			
			//checking for PDF document layout
			$docspecs = $pdf->getTemplateSize($pdfTpl);
			$pdf->AddPage($docspecs['h'] > $docspecs['w'] ? "P" : "L");
			
			//Use this page as template
			$pdf->useTemplate($pdfTpl);
		}
		
		$pdf->Output(str_replace('(Modified).pdf', '(Annotated).pdf', $dPath), "F");
		$dPath = str_replace('(Modified).pdf', '(Annotated).pdf', $dPath);

	}
	
	
	
?>