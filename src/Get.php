<?php
/**
 * The file for the get-style service
 *
 * @author     Jack Clayton <clayjs0@gmail.com>
 * @copyright  2016 Jack Clayton
 * @license    MIT
 */

namespace Jstewmc\GetStyle;

use InvalidArgumentException;

/**
 * The get-style service
 *
 * @since  0.1.0
 */
class Get 
{
    /* !Private properties */
    
    /**
     * @var    mixed[]  an array of css declarations indexed by name (e.g., ["foo" => 
     *     ["color" => "red", "padding-top": "10px", ...], ...]) (keep in mind, the 
     *     name "*" is the keyword for all)
     * @since  0.1.0
     */ 
    private $styles;
    
    
    /* !Magic methods */
    
    /**
     * Called when the service is constructed
     *
     * @param  mixed[]
     * @since  0.1.0
     */
    public function __construct(array $styles)
    {
        $this->styles = $styles;
    }
    
    /**
     * Called when the service is treated like a function
     *
     * @param   string    $names  the style names (e.g., "foo bar baz") to use 
     *     (optional; if omitted, defaults to global style)
     * @param   string[]  $declarations  an array of custom declaractions to use
     *     (optional; if omitted, defaults to an empty array)
     * @return  string
     */
    public function __invoke(string $names = null, array $declarations = []): string
    {
        $css = '';
        
        // get the requested style declarations
        $style = $this->getStyle($names);
        
        // merge the custom declarations into the requested declarations
        $declarations = array_merge($style, $declarations);
    		
        // get the declarations as inline css
        $css = $this->getCss($declarations);
    	
    	return $css;
    }
    
    
    /* !Private properties */
    
    /**
     * Returns the declarations as css
     *
     * I'll return a string of inline css for the style declarations. 
     *
     * Keep in mind, I'll include a space between the property and declaration as 
     * well as after the the delcaration for devices with strict line lengths (like 
     * some email clients).
     *
     * @param   string[]  $declarations  an array of style declarations
     * @return  string
     * @since   0.1.0
     */
    private function getCss(array $declarations): string
    {
        $css = '';
        
        // loop through the declarations
    	foreach ($declarations as $property => &$declaration) {
    		// convert the declaration to a string...
    		// keep in mind, add spaces between the property and declaration
    		//
    		$declaration = "$property: $declaration;";
    	}
    	
    	// separate the declarations by space
    	$css = implode(' ', $declarations);
    	
    	return $css;
    }
    
    /**
     * Returns the named style declarations
     *
     * @param   string  $names  a string of space-separated style names (optional)
     * @return  string[]
     * @throws  InvalidArgumentException  if name in $names does not exist
     * @since   0.1.0
     */
    private function getStyle(string $names = null): array
    {
        $declarations = [];
        
        // explode the names into an array
        $names = array_filter(explode(' ', (string) $names), 'strlen');
        
        // if a global style exists, prepend it to the names array
        if (array_key_exists('*', $this->styles)) {
            array_unshift($names, '*');    
        }
        
        // if the $names array is empty, short-circuit
        if (count($names) === 0) {
            return $declarations;
        }
        
        // otherwise, loop through the names
    	foreach ($names as $name) {
    		// if the style does not exist, short-circuit
    		if ( ! array_key_exists($name, $this->styles)) {
        		throw new InvalidArgumentException(
            		__METHOD__ . "() expects name '$name' to exist in styles"
        		);
    		}
			// otherwise, merge the style's declarations into the array...
			// keep in mind, the later declarations should overwrite the 
			//     previous declarations
			//
			$declarations = array_merge($declarations, $this->styles[$name]);
    	}
    	
    	return $declarations;
    }
}
