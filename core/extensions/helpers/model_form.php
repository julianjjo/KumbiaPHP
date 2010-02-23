<?php
/**
 * KumbiaPHP web & app Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://wiki.kumbiaphp.com/Licencia
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@kumbiaphp.com so we can send you a copy immediately.
 *
 * Helper para crear Forms de un modelo automáticamente
 * 
 * @category   KumbiaPHP
 * @package    Helpers 
 * @copyright  Copyright (c) 2005-2009 KumbiaPHP Team (http://www.kumbiaphp.com)
 * @license    http://wiki.kumbiaphp.com/Licencia     New BSD License
 */

class ModelForm {
	/**
     * Genera un form de un modelo (objeto) automáticamente
     *
     * @var object 
     **/
	public static function create($model, $action = NULL) {
	
		$model_name = Util::smallcase(get_class($model));
		if(! $action) $action = ltrim(Router::get('route'),'/');
		
		echo '<form action="',URL_PATH.$action,'" method="post" id="',$model_name,'" class="scaffold">'.PHP_EOL; 
		
		echo '<input id="',$model_name,'_id" name="',$model_name,'[id]" class="id" value="',$model->id.'" type="hidden">'.PHP_EOL; 
		
		$fields = array_diff($model->fields, $model->_at, $model->_in, $model->primary_key);
		
		foreach ($fields as $field){
			
			$tipo = trim(preg_replace('/(\(.*\))/','',$model->_data_type[$field]));//TODO: recoger tamaño y otros valores
			$alias = $model->get_alias($field);
			$formId = $model_name.'.'.$field;
			$formName = $model_name.'['.$field.']';
			
			if (in_array($field, $model->not_null)){
				echo "<label for=\"$formId\" class=\"required\">$alias *</label>".PHP_EOL; 
			} else echo "<label for=\"$formId\">$alias</label>".PHP_EOL; 
			
			switch ($tipo){
						case 'tinyint': case 'smallint': case 'mediumint':
						case 'integer': case 'int': case 'bigint':
						case 'float': case 'double': case 'precision':
						case 'real': case 'decimal': case 'numeric':
						case 'year': case 'day': case 'int unsigned': // Números
							
							echo "<input id=\"$formId\" type=\"number\" name=\"$formName\" value=\"{$model->$field}\">".PHP_EOL ; 
							break;
						
						
						case 'date': // Usar el js de datetime
							echo "<input id=\"$formId\" type=\"date\" name=\"$formName\" value=\"{$model->$field}\">".PHP_EOL;
							break;
						case 'datetime': case 'timestamp':
							echo "<input id=\"$formId\" type=\"datetime\" name=\"$formName\" value=\"{$model->$field}\">".PHP_EOL;

							//echo '<script type="text/javascript" src="/javascript/kumbia/jscalendar/calendar.js"></script>
							//<script type="text/javascript" src="/javascript/kumbia/jscalendar/calendar-setup.js"></script>
							//<script type="text/javascript" src="/javascript/kumbia/jscalendar/calendar-es.js"></script>'.PHP_EOL;
							//echo date_field_tag("$formId");
							break;
						
						case 'enum': case 'set': case 'bool':
						// Intentar usar select y lo mismo para los field_id
							echo "<input id=\"$formId\" class=\"select\" name=\"$formName\" type=\"text\" value=\"{$model->$field}\">".PHP_EOL;
							break;
						
						case 'text': case 'mediumtext': case 'longtext':
						case 'blob': case 'mediumblob': case 'longblob': // Usar textarea
							echo "<textarea id=\"$formId\" name=\"$formName\">{$model->$field}</textarea>".PHP_EOL;
							break;
						
						default: //text,tinytext,varchar, char,etc se comprobara su tamaño
							echo "<input id=\"$formId\" type=\"text\" name=\"$formName\" value=\"{$model->$field}\">".PHP_EOL;
							//break;					
			
			}
		}
		//echo radio_field_tag("actuacion", array("U" => "una", "D" => "dos", "N" => "Nada"), "value: N");
		echo '<input type="submit" value="Enviar" />'.PHP_EOL;
		echo '</form>'.PHP_EOL;
	}
}