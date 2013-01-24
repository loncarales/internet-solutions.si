<?php
/**
 * Wordpress Form Builder Utility Class
 * 
 * A group of classes designed to make it easier and quicker to create forms 
 * within wordpress plugins for the admin section. Using this class should hopefully 
 * reduce development and debugging time.
 * 
 * This code is very much in alpha phase, and should not be distributed with plugins 
 * other than by Dan Harrison.
 * 
 * @author Dan Harrison (http://www.danharrison.co.uk)
 *
 * Version History
 * 
 * V0.01 - Initial version released.
 * V0.02 - Added support for uploading files.
 * V0.03 - Added support for submission checking and form validation.
 * V0.04 - Added checkbox list support.
 * 		   Added section break code.
 * 
 */


/**
 * Class that represents a HTML form for the Wordpress admin area.
 */
if (!class_exists('FormBuilder')) { class FormBuilder {

	/**
	 * A list of the elements to go in the HTML form. 
	 * @var Array
	 */
	private $elementList;
	
	/**
	 * A list of the elements of where a break is needed.
	 * @var Array
	 */
	private $breakList;
	
	/**
	 * The form name, used for the name attribute of the form.
	 * @var String The name of the form.
	 */
	private $formName;
	
	/**
	 * A list of the buttons to go in the HTML form. 
	 * @var Array
	 */
	private $buttonList;	
		
	/**
	 * The text used on the submit button.
	 * @var String The text used on the submit button.
	 */
	private $submitlabel;	
	
	/**
	 * A list of the errors that have occured for this form.
	 * @var Array A list of errors with this form.
	 */
	private $errorList;
	
	/**
	 * Constructor
	 */
	function FormBuilder($name = false)
	{
		$this->elementList = array();
		$this->buttonList = array();
		$this->setSubmitLabel(false);
		$this->formName = $name;
		$this->errorList = array();
		$this->breakList = array();
	}
		
	/**
	 * Set the label for the submit button to the specified text. If the specified label is blank, 
	 * then "Update Settings" is used as a default.
	 * 
	 * @param $label The text to use for the submit button.
	 */
	function setSubmitLabel($label)
	{
		// Only update if $label is a valid string, otherwise set default.
		if ($label)
			$this->submitlabel = $label;
		else 
			$this->submitlabel = "Update Settings";
	}
	
	/**
	 * Add the specified form element to the internal list of elements to put on the form.
	 * @param $formElement A <code>FormElement</code> object to add to the form.
	 */
	function addFormElement($formElement) {
		array_push($this->elementList, $formElement);
	}
	
	/**
	 * Add a button to be added to the end of the form.
	 * @param $buttonName The name of the button.
	 * @param $buttonText The text to be used for the button itself.
	 */
	function addButton($buttonName, $buttonText) {
		$this->buttonList[$buttonName] = $buttonText;
	}
	
	/**
	 * Add a break at the current position in the form where form fields are being added. 
	 * If no form elements have been added, no break is created.
	 * 
	 * @param $sectionID The string to use as the section ID for the section we've created.
	 * @param $prefixHTML The HTML to add before the section break if specified.
	 */
	function addBreak($sectionID, $prefixHTML = false)
	{
		// Get the latest element to have been added to the array		
		$latestElement = end($this->elementList);
		
		// Nowhere to add a break
		if ($latestElement === FALSE) {
			return false;
		}
		
		// Somewhere to add a break, so use form field name
		// as a pointer of where to add break
		$this->breakList[$latestElement->name] = array('sectionid' => $sectionID, 
													   'prefixHTML' => $prefixHTML
													); 
	}
	
	
	/**
	 * Determine if one of the fields in this form is an upload file field.
	 * @return Boolean True if there is a file upload field, false otherwise.
	 */
	function haveFileUploadField()
	{
		$haveUploadField = false;
		foreach ($this->elementList as $element)
		{
			if ($element->type == 'uploadfile') {
				$haveUploadField = true;
				break;
			}
		}
		
		return $haveUploadField;
	}
	
	
	/**
	 * Generates the HTML for the form object.
	 * @return String The HTML for this form object.
	 */
	function toString()
	{
		// Start main form attributes
		$formAttributes = array();
		$formAttributes['method'] = 'POST';	
		$formAttributes['action'] = str_replace( '%7E', '~', $_SERVER['REQUEST_URI']);
		
		// Add the form name if specified
		$namestring = "";
		if ($this->formName) {
			$formAttributes['name'] = $this->formName;	
		}
		
		// Need extra attribute if there's a upload field
		if ($this->haveFileUploadField()) {
			$formAttributes['enctype'] = 'multipart/form-data';
		}
		
		// Render form with all attributes
		$attributeString = false;
		foreach($formAttributes as $name => $value) {
			$attributeString .= sprintf('%s="%s" ', $name, $value);
		}
		
		// Start form
		$resultString = "\n<form $attributeString>\n";
		$resultString .= $this->createTableHeader();
		
		// Now add all form elements
		foreach ($this->elementList as $element)
		{
			// Hidden elements are added later
			if ($element->type == 'hidden') {
				continue;
			}
			
			// Render form element				
			$resultString .= $element->toString();

			// Add section breaks if this element is in the break list.
			// Add break after element HTML
			if (in_array($element->name, array_keys($this->breakList)))
			{
				$resultString .= $this->createTableFooter();
				$resultString .= $this->createTableHeader(array('id' => $this->breakList[$element->name]['sectionid']), $this->breakList[$element->name]['prefixHTML']);
			}
			
		}
		
		$resultString .= $this->createTableFooter();
		
		// Button area
		$resultString .= '<p class="submit">'."\n";
		
		// Add submit button
		$resultString .= "\t".'<input class="button-primary" type="submit" name="Submit" value="'.$this->submitlabel.'" />'."\n";
		
		// Add remaining buttons
		foreach ($this->buttonList as $buttonName => $buttonLabel) {
			$resultString .= "\t<input type=\"submit\" name=\"$buttonName\" value=\"$buttonLabel\" />\n";		
		}
				
		// Hidden field to indicate update is happening
		$resultString .= "\t".'<input type="hidden" name="update" value="update" />'."\n";
				
		// Add any extra hidden elements
		foreach ($this->elementList as $element)
		{
			// Leave all hidden elements until the end.
			if ($element->type == 'hidden') {	
				$resultString .= "\t".'<input type="hidden" name="'.$element->name.'" value="'.$element->value.'" />'."\n";
			}
		}		
		
		$resultString .= '</p>'."\n";
							
		// End form
		$resultString .= "\n</form>\n";
		
		return $resultString;
	}
	
	/**
	 * Return string to start a HTML table.
	 * @return String HTML to start a HTML table.
	 * @return String The HTML to put before the HTML table if specified.
	 */	
	private function createTableHeader($attributeList = false, $prefixHTML = false)
	{
		// Render table with all specified attributes
		$attributeString = false;
		if ($attributeList)
		{
			foreach($attributeList as $name => $value) {
				$attributeString .= sprintf('%s="%s" ', $name, $value);
			}
		}		
		
		return "$prefixHTML\n\n<table class=\"form-table\" $attributeString>\n";
	}
	
	/**
	 * Return string to terminate a HTML table.
	 * @return String HTML to terminate a HTML table.
	 */
	private function createTableFooter() {
		return "</table>\n";
	}
	
	/**
	 * Determine if the form has been submitted
	 * @return Boolean True if the form has been submitted, false otherwise.
	 */
	function formSubmitted() {
		return (isset($_POST['update']));
	}
	
	/**
	 * Get the list of errors following a form validation.
	 * @return unknown_type
	 */
	function getListOfErrors() {
		return $this->errorList;
	}
	
	/**
	 * Determine if the form is valid
	 * @return Boolean True if the form is valid, false otherwise. False is also returned if the form has not been submitted.
	 */
	function formValid()
	{
		// Not submitted, so can't be valid.
		if (!$this->formSubmitted())
			return false;
						
		// Empty error list
		$this->errorList = array();
						
		// Check each field is valid.
		foreach ($this->elementList as $element)
		{
			// Elements with lots of selected values, so copy list of values across.
			if ($element->type == 'checkboxlist') 
			{	
				// Dynamic function to retrieve all fields that start with the element name
				// for multi-item lists.			
				$filterFunc = create_function('$v', '$filterStr = "'.$element->name.'_"; return (substr($v, 0, strlen($filterStr)) == $filterStr);');
				
				// Extract all values for this multi-item list.
				$itemList = array_filter(array_keys($_POST), $filterFunc);
				
				// If we've got some values, extract just the values of the list				
				if (count($itemList) > 0) 
				{
					$element->value = array();
					$regexp = sprintf('/%s_(.*)/', $element->name);
					
					foreach ($itemList as $fieldname)
					{
						// Extract the actual field name from the list, and then assign it 
						// to the internal list of values for this particular multi-item field.
						if (preg_match($regexp, $fieldname, $matches)) {
							
							// Proper value is still held in $_POST, so retrieve it using
							// full name of field (field name plus sub-item name)
							$element->value[$matches[1]] = WPL_getArrayValue($_POST, $fieldname);  
						}
					}
				}	
			}
			
			// Single value element - just copy standard post value
			else {
				$element->value = $_POST[$element->name];
			}
			
			// Validate the element
			if (!$element->isValid())
			{
				// Add error to internal list of errors for this form.
				$this->errorList[] = $element->getErrorMessage();
			}
		}
		
		// If we have errors, clearly the form is not valid
		return (count($this->errorList) == 0); 
	}
	
	/**
	 * Get the values for this submitted form.
	 * @param $elementList If specified, return just the values for these elements. If false, return all values for this form.
	 * @return Array A list of all the submitted field names => values for this form.
	 */
	function getFormValues($selectList = false)
	{
		if (!$this->formSubmitted())
			return false;
			
		$returnList = array();
		foreach ($this->elementList as $element)
		{
			if ($selectList && is_array($selectList))
			{
				// Only add if in the list of specified elements.
				if (in_array($element->name, $selectList)) {
					$returnList[$element->name] = $element-> value;
				}
			} 
			// Add anyway, no list to choose from.
			else {
				$returnList[$element->name] = $element-> value;
			}
			
		} // end of foreach
		
		return $returnList;
	}
	
	/**
	 * Set the default values for this form.
	 * @param $valueList The list of field name => field value pairs.
	 */
	function setDefaultValues($valueList)
	{
		if (!$valueList) {
			return;
		}

		// Iterate through form fields checking if there's a default value to
		// use, because we don't have an associative list of elements
		foreach ($this->elementList as $element)
		{			
			// Do we have a default value for this field?
			if (isset($valueList[$element->name])) {
				$element->value = $valueList[$element->name];
			}
		} //end foreach
	}
}


/**
 * Class that represents a HTML form element for the Wordpress admin area.
 */
class FormElement {
	
	/**
	 * The different types of form element, including <code>select</code>, <code>text</code>, 
	 * <code>checkbox</code>, <code>hidden</code> and <code>textarea</code>.  
	 *    
	 * @var String The type of the form element.
	 */
	public $type;	
	
	/**
	 * The current value of this form element.
	 * @var String The current value of this form element.
	 */
	public $value;
	
	/**
	 * The label for this form element.
	 * @var String The descriptive label for this form element.
	 */
	public $label;
	
	/**
	 * The <code>name</code> of the form element, as in the HTML attribute name.
	 * @var String The HTML attribute name of this element.
	 */
	public $name;
	
	/**
	 * The description of this form element, that typically goes after the element.
	 * @var String The description of this form element.
	 */
	public $description;
	
	/**
	 * Boolean flag to determine if the field is a form field (which if true, automatically adjusts the entry field to fit the screen size)
	 * @var Boolean True if this is a form field, false otherwise.
	 */
	public $isformfield;
	
	/**
	 * The number of rows to use in a text area.
	 * @var Integer the number of rows to use in a text area.
	 */
	public $textarea_rows;

	/**
	 * The number of columns to use in a text area.
	 * @var Integer the number of columns to use in a text area.
	 */	
	public $textarea_cols;
	
	/**
	 * The list of items used in an HTML select box.
	 * @var Array
	 */
	public $select_itemlist;
	
	/**
	 * The label for a checkbox.
	 * @var String The text that goes next to a checkbox.
	 */
	public $checkbox_label;
	
	/**
	 * The CSS class to set the HTML form element to.
	 * @var String The CSS class to set teh HTML form element to.
	 */
	public $cssclass;
	
	/**
	 * HTML rendered after the form element, but before the description.
	 * @var String The HTML used to go after the form element. 
	 */
	public $afterFormElementHTML;	

	/**
	 * HTML used to create a custom form element.
	 * @var String The HTML to create a custom form element.
	 */
	private $customHTML;	
	
	/**
	 * Is this form value required? 
	 * @var Boolean True if required, false if otherwise.
	 */
	public $required; 
	
	/**
	 * The message to show if there's something wrong with this error message.
	 * @var String The error message.
	 */
	public $errorMessage;
	
	/**
	 * Function that validate this data field.
	 * @var Function Reference to a function used to validate this data field.
	 */
	public $validationFn;
	
	/**
	 * Constructor
	 */
	function FormElement($name, $label, $required = false)
	{
		$this->name  = $name;
		$this->label = $label;		
		$this->required = $required;
		
		// Default type is text
		$this->type = "text";
		
		// Set defaults for text area
		$this->textarea_rows = 4;
		$this->textarea_cols = 70;		
		
		// A formfield by default
		$this->isformfield = true;
		$this->customHTML = false;
	}	
	
	/**
	 * Sets this element to be a checkbox.
	 */
	function setTypeAsCheckbox($labeltext = false)
	{
		$this->type = "checkbox";
		$this->checkbox_label = $labeltext;
		
		// Formfield doesn't work if a checkbox
		$this->isformfield = false;
	}
	
	
	/**
	 * Set the type of this element to be a text area with the specified number of rows and columns. 
	 * @param $rows The number of rows for this text area, the default is 4.
	 * @param $cols The number of columns for this text area, the default is 70.
	 */
	function setTypeAsTextArea($rows = 4, $cols = 70) {
		$this->type = "textarea";
		$this->textarea_cols = $cols;
		$this->textarea_rows = $rows;
	}
	
	/**
	 * Sets this element to be a hidden element.
	 */
	function setTypeAsHidden() {
		$this->type = "hidden";
	}
	
	/**
	 * Sets the type to be static, where the value is used rather than a normal form field.
	 */
	function setTypeAsStatic() {
		$this->type = "static";
	}
	
	/**
	 * Sets the type to be a file upload form, where a uploader box is used rather than a normal form field.
	 */
	function setTypeAsUploadFile() {
		$this->type = "uploadfile";
	}	
		
	/**
	 * Sets the element type to be a combo box (A SELECT element in HTML). The specified list of 
	 * items can be a simple list (e.g. x, y, z), or a list of values mapping to a description 
	 * (e.g. a => 1, b => 2, c => 3). However, in the case of a simple list, the values will be 
	 * interpreted as their actual index e.g. (0 => x, 1 => y, 2 => z). If the value of this element
	 * matches one of the options in the list, then that option will be selected when the HTML is rendered.
	 * 
	 * @param $itemList The list of items to set in the combo box.
	 */
	function setTypeAsComboBox($itemList) {
		$this->type = "select";
		$this->select_itemlist = $itemList;
	}
	
	/**
	 * Sets the element type to be a checkbox list. The specified list of items can be a simple list 
	 * (e.g. x, y, z), or a list of values mapping to a description (e.g. a => 1, b => 2, c => 3). 
	 * However, in the case of a simple list, the values will be interpreted as their actual index 
	 * e.g. (0 => x, 1 => y, 2 => z). If any of the values are marked as on, then that checkbox will
	 * be ticked when the HTML is rendered.
	 * 
	 * @param $itemList The list of items to create tickboxes for.
	 */
	function setTypeAsCheckboxList($itemList) {
		$this->type = "checkboxlist";
		$this->select_itemlist = $itemList;
	}	

	
	/**
	 * Sets this element to be a custom element using the specified HTML to create a form field.
	 */
	function setTypeAsCustom($HTML) {
		$this->type = "custom";
		$this->customHTML = $HTML;
	}
	
	/**
	 * Render the current form element as an HTML string.
	 * @return String This form element as an HTML string.
	 */
	function toString() {
		
		// Formfield class, on by default
		$trclass = ' class="form-field"';
		if (!$this->isformfield) {
			$trclass = "";
		}
		
		$elementString = "<tr valign=\"top\"$trclass>\n";

		// The label
		$elementString .= "\t".'<th scope="row"><label for="'.$this->name.'">'.$this->label.'</label></th>'."\n";		
		
		// Start the table data for the form element and description 
		$elementString .= "\t<td>\n\t\t";

		if ($this->cssclass) {
			$elementclass = "class=\"$this->cssclass\"";
		}
		
		// The actual form element
		switch ($this->type)
		{
			case 'select':
				$elementString .= "<select name=\"$this->name\" $elementclass>";
				foreach ($this->select_itemlist AS $value => $label)
				{
					$htmlselected = "";
					if ($value == $this->value) {
						$htmlselected = ' selected="selected"';
					}
					
					$elementString .= "\n\t\t\t";
					$elementString .= '<option value="'.$value.'"'.$htmlselected.'>'.$label.'&nbsp;&nbsp;</option>';
				}
				$elementString .= "\n</select>";
				break; 
			
			case 'textarea':
				$elementString .= "<textarea name=\"$this->name\" rows=\"$this->textarea_rows\" cols=\"$this->textarea_cols\" $elementclass>$this->value</textarea>";  
				break; 

			case 'uploadfile':
				$elementString .= "<input type=\"file\" name=\"$this->name\" $elementclass/>";  
				break; 				
				
			case 'checkbox':
				$checked = "";
				if ($this->value == 1 || $this->value == "on")
					$checked = ' checked=checked';
				
				$elementString .= "<input type=\"checkbox\" name=\"$this->name\" $checked $elementclass/> $this->checkbox_label";  
				break;
				
			case 'checkboxlist':
				foreach ($this->select_itemlist AS $value => $label)
				{
					$htmlselected = "";
					if (is_array($this->value) && array_key_exists($value, $this->value)) {
						$htmlselected = ' checked="checked"';
					}
					
					$elementString .= "\n\t\t\t";
					$elementString .= sprintf('<input type="checkbox" name="%s_%s" %s style="width: auto"/>&nbsp;%s<br/>', 
										$this->name, 
										$value,
										$htmlselected, 
										$label
									);
				}
				$elementString .= "\n";				
				break; 
							
			/* A static type is just the value field. */
			case 'static':
				$elementString .= $this->value;
				break;
				
			/* Custom elements - just dump the provided HTML */
			case 'custom':
				$elementString .= $this->customHTML;
				break;
				
			/* The default is just a normal text box. */
			default:
				// Add a default style
				if (!$this->cssclass) {
					$elementclass = 'class="regular-text"';
				}
					
				$elementString .= "<input type=\"text\" name=\"$this->name\" value=\"$this->value\" $elementclass/>";
				break; 
		}
		
		$elementString .= "\n";
				
		// Add extra HTML after form element if specified
		if ($this->afterFormElementHTML) {
			$elementString .= $this->afterFormElementHTML . "\n";
		}
		
		// Only add description if one exists.
		if ($this->description) {
			$elementString .= "\t\t".'<span class="setting-description"><br>'.$this->description.'</span>'."\n";
		}
		
		$elementString .= "\t</td>\n";
		
		// All done
		$elementString .= '</tr>'."\n";
		return $elementString;
	}
	
	
	/**
	 * Determines if the value for this field is valid.
	 * @return Boolean True if the value is valid, false otherwise.
	 */
	function isValid()
	{
		// Non-user entries are always valid
		if ($this->type == 'static' || $this->type == 'hidden')
			return true;
			
		// For multi-item lists, should be at least one value
		if ($this->type == 'checkboxlist') {
			if ($this->required) {
				return is_array($this->value) && count($this->value) > 0;
			} 
			// If not required, then always valid.
			else {
				return true;
			}
		}
			
		// Field is required, but empty
		if ($this->required && $this->value == false) 
			return false; 
			
		// Field is not required, and empty
		if (!$this->required && $this->value == false) 
			return true;
			
		// Field needs validation, but no validation function, so return true.
		if (!$this->validationFn) 			
			return true;
			
		// Finally, we've got a validation function, so use it.
		return $this->validationFn($this->value, $this->name);
	}
	
	
	/**
	 * Get the error message if there's something wrong with this form field.
	 * @return String The error message if there's something wrong with this field.
	 */
	function getErrorMessage()
	{
		// Ah, we have a custom message, use that.
		if ($this->errorMessage) {
			return $this->errorMessage;
		}
		
		// Field is required, but empty, so return a fill in this form message.
		else if ($this->required && $this->value == false) 
			return sprintf("Please fill in the required '%s' field.", $this->label);
	
		// Have we got an empty error message? Create a default one
		else if (!$this->errorMessage) {
			return sprintf("There's a problem with value for '%s'.", $this->label);
		} 
	}
	

	
}}

?>