<?php
class formTemplate {
	

	protected $_form;
	protected $_openSyntax;
	protected $_closeSyntax;
	protected $_preLabel;
	protected $_postLabel;



	public function __construct($formname, $action, $title, $intro, $class = '') {
		$this->_form = "<h3 class='formtitle'>$title</h3>";
		$this->_form .= "<p class='intro' align='justify'>$intro</p>";
		$this->_form .= "<form name='$formname' action='$action' method='POST' class='$class'>";
	}


	public function setOpenSyntax($syntax) {
		$this->_openSyntax = $syntax;
	}

	public function setCloseSyntax($syntax) {
		$this->_closeSyntax = $syntax;
	}



	public function preLabel($syntax) {
		$this->_preLabel = $syntax;
	}

	public function postLabel($syntax) {
		$this->_postLabel = $syntax;
	}

	public function addField($input, $label = '', $showSyntax = true, $inputFirst = false) {
        $this->_form .=($showSyntax)? $this->_openSyntax : '';
        if($inputFirst) {
        	$this->_form .= $input;
        	$this->_form .= $this->_preLabel;
			$this->_form .= $label;
			$this->_form .= $this->_postLabel;
        } else {
			$this->_form .= $this->_preLabel;
			$this->_form .= $label;
			$this->_form .= $this->_postLabel;
			$this->_form .= $input;
		}
		$this->_form .= ($showSyntax)? $this->_closeSyntax : '';
	}

	public function addElement($element) {
		$this->_form .= $element;
	}

	public function getForm() {
		$this->_form .= '</form>';
		return $this->_form;
	}
}
?>
