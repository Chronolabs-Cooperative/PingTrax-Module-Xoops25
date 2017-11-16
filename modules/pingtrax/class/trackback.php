<?php
/**
 * PingTrax Constructor for Plugin's
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright   Chronolabs Cooperative http://sourceforge.net/projects/chronolabs/
 * @license     GNU GPL 3 (http://labs.coop/briefs/legal/general-public-licence/13,3.html)
 * @author      Simon Antony Roberts <wishcraft@users.sourceforge.net>
 * @see			http://sourceforge.net/projects/xoops/
 * @see			http://sourceforge.net/projects/chronolabs/
 * @see			http://sourceforge.net/projects/chronolabsapi/
 * @see			http://labs.coop
 * @version     1.0.1
 * @since		1.0.1
 */

defined('XOOPS_ROOT_PATH') || die('XOOPS root path not defined');

/**
 * Class PingtraxTrackback
 * 
 * @param string $blog_name 
 * @param string $author 
 * @param string $encoding 
 */
class PingtraxTrackback {
	
    var $blog_name = ''; 		// Default blog name used throughout the class (ie. Chronolabs Cooperative)
    var $author = ''; 			// Default author name used throughout the class (ie. Simon Antony Roberts)
    var $encoding = ''; 		// Default encoding used throughout the class (ie. UTF-8)
    var $get_id = ''; 			// Retreives and holds $_GET['id'] (if not empty)
    var $post_id = ''; 			// Retreives and holds $_POST['id'] (if not empty)
    var $url = ''; 				// Retreives and holds $_POST['url'] (if not empty)
    var $title = ''; 			// Retreives and holds $_POST['title'] (if not empty)
    var $expert = ''; 			// Retreives and holds $_POST['expert'] (if not empty)
    /**
     * Class Constructure
     * 
     * @param string $blog_name 
     * @param string $author 
     * @param string $encoding 
     * @return 
     */
    function PingtraxTrackback($blog_name, $author, $encoding = "UTF-8")
    {
        $this->blog_name = $blog_name;
        $this->author = $author;
        $this->encoding = $encoding; 
		
        // Gather $_POST information
        if (isset($_GET['id'])) {
            $this->get_id = $_GET['id'];
        } 
        if (isset($_POST['id'])) {
            $this->post_id = $_POST['id'];
        } 
        if (isset($_POST['url'])) {
            $this->url = $_POST['url'];
        } 
        if (isset($_POST['title'])) {
            $this->title = $_POST['url'];
        } 
        if (isset($_POST['expert'])) {
            $this->expert = $_POST['expert'];
        } 
    } 

    /**
     * Sends a trackback ping to a specified trackback URL.
     * allowing clients to auto-discover the TrackBack Ping URL. 
     * 
     * <code><?php
     * include('trackback.php');
     * $trackback = new PingtraxTrackback('Chronolabs Cooperative', 'Simon Antony Roberts', 'UTF-8');
     * if ($trackback->ping('http://example.com/modules/pingtrax/api/', 'http://your-url.com', 'Your entry title')) {
     * 	echo "PingtraxTrackback sent successfully...";
     * } else {
     * 	echo "Error sending trackback....";
     * }
     * ?></code>
     * 
     * @param string $trackback 
     * @param string $url 
     * @param string $title 
     * @param string $excerpt 
     * @return boolean 
     */
    function ping($trackback, $url, $title = "", $excerpt = "")
    {
        $response = "";
        $reason = ""; 
        // Set default values
        if (empty($title)) {
            $title = "PingtraxTrackbacking your entry...";
        } 
        if (empty($excerpt)) {
            $excerpt = "I found your entry interesting do I've added a PingtraxTrackback to it on my weblog :)";
        } 
        // Parse the target
        $target = parse_url($trackback);

        if ((isset($target["query"])) && ($target["query"] != "")) {
            $target["query"] = "?" . $target["query"];
        } else {
            $target["query"] = "";
        } 

        if ((isset($target["port"]) && !is_numeric($target["port"])) || (!isset($target["port"]))) {
            $target["port"] = 80;
        } 
        // Open the socket
        $trackback_sock = fsockopen($target["host"], $target["port"]); 
        // Something didn't work out, return
        if (!is_resource($trackback_sock)) {
            return '$trackback->ping: can\'t connect to: ' . $trackback . '.';
            exit;
        } 
        // Put together the things we want to send
        $trackback_send = "url=" . rawurlencode($url) . "&title=" . rawurlencode($title) . "&blog_name=" . rawurlencode($this->blog_name) . "&excerpt=" . rawurlencode($excerpt); 
        // Send the trackback
        fputs($trackback_sock, "POST " . $target["path"] . $target["query"] . " HTTP/1.1\r\n");
        fputs($trackback_sock, "Host: " . $target["host"] . "\r\n");
        fputs($trackback_sock, "Content-type: application/x-www-form-urlencoded\r\n");
        fputs($trackback_sock, "Content-length: " . strlen($trackback_send) . "\r\n");
        fputs($trackback_sock, "Connection: close\r\n\r\n");
        fputs($trackback_sock, $trackback_send); 
        // Gather result
        while (!feof($trackback_sock)) {
            $response .= fgets($trackback_sock, 128);
        } 
        // Close socket
        fclose($trackback_sock); 
        // Did the trackback ping work
        strpos($response, '<error>0</error>') ? $return = true : $return = false; 
        // send result
        return $return;
    } 

    /**
     * Produces XML response for trackbackers with ok/error message.
     * 
     * <code><?php
     * // Set page header to XML
     * header('Content-Type: text/xml'); // MUST be the 1st line
     * //
	 * // Instantiate the class
	 * //
     * include('trackback.php');
     * $trackback = new PingtraxTrackback('Chronolabs Cooperative', 'Simon Antony Roberts', 'UTF-8');
	 * //
     * // Get trackback information
	 * //
     * $trackback_id = $trackback->post_id; // The id of the item being trackbacked
     * $trackback_url = $trackback->url; // The URL from which we got the trackback
     * $trackback_title = $trackback->title; // Subject/title send by trackback
     * $trackback_expert = $trackback->expert; // Short text send by trackback
	 * //  
     * // Do whatever to log the trackback (save in DB, flatfile, etc...)
	 * //
     * if (TRACKBACK_LOGGED_SUCCESSFULLY) {
     * 	// Logged successfully...
     * 	echo $trackback->recieve(true);
     * } else {
     * 	// Something went wrong...
     * 	echo $trackback->recieve(false, 'Explain why you return error');
     * }
     * ?></code>
     * 
     * @param boolean $success 
     * @param string $err_response 
     * @return boolean 
     */
    function recieve($success = false, $err_response = "")
    { 
        // Default error response in case of problems...
        if (!$success && empty($err_response)) {
            $err_response = "An error occured while tring to log your trackback...";
        } 
        // Start response to trackbacker...
        $return = '<?xml version="1.0" encoding="' . $this->encoding . '"?>' . "\n";
        $return .= "<response> \n"; 
        // Send back response...
        if ($success) {
            // PingtraxTrackback received successfully...
            $return .= "	<error>0</error> \n";
        } else {
            // Something went wrong...
            $return .= "	<error>1</error> \n";
            $return .= "	<message>" . $this->xml_safe($err_response) . "</message>\n";
        } 
        // End response to trackbacker...
        $return .= "</response>";

        return $return;
    } 

    /**
     * Feteched trackback information and produces an RSS-0.91 feed.
     * 
     * <code><?php
     * // 1
     * header('Content-Type: text/xml'); // MUST be the 1st line
     * // 2
     * include('trackback.php');
     * $trackback = new PingtraxTrackback('Chronolabs Cooperative', 'Simon Antony Roberts', 'UTF-8');
     * // 3
     * $trackback_id = $trackback->get_id;
     * // 4
     * Do whatever to get trackback information by ID (search db, etc...)
     * if (GOT_TRACKBACK_INFO) {
     * 	// Successful - pass trackback info as array()...
     * 	$trackback_info = array('title' => string TRACKBACK_TITLE, 
     * 			'expert'	=> string TRACKBACK_EXPERT,
     * 			'permalink' => string PERMALINK_URL,
     * 			'trackback' => string TRACKBACK_URL
     * 		);
     * 	echo $trackback->fetch(true, $trackback_info);
     * } else {
     * 	// Something went wrong - tell my why...
     * 	echo $trackback->fetch(false, string RESPONSE);
     * }
     * ?></code>
     * 
     * @param boolean $success 
     * @param string $response 
     * @return string XML response to the caller
     */
    function fetch($success = false, $response = "")
    {
        if (!$success && empty($response)) {
            $response = "An error occured while tring to retreive trackback information...";
        } 
        // Start response to caller
        $return = '<?xml version="1.0" encoding="' . $this->encoding . '"?>' . "\n";
        $return .= "<response> \n"; 
        // Send back response...
        if ($success) {
            // PingtraxTrackback retreived successfully...
            // Sending back an RSS (0.91) - trackback information from $response (array)...
            $return .= "\t<error>0</error> \n";
            $return .= "\t<rss version=\"0.91\"> \n";
            $return .= "\t<channel> \n";
            $return .= "\t\t<title>" . $this->xml_safe($response['title']) . "</title> \n";
            $return .= "\t\t<link>" . $this->xml_safe($response['trackback']) . "</link> \n";
            $return .= "\t\t<description>" . $this->xml_safe($response['expert']) . "</description> \n";
            $return .= "\t\t<item> \n";
            $return .= "\t\t\t<title>" . $this->xml_safe($response['title']) . "</title> \n";
            $return .= "\t\t\t<link>" . $this->xml_safe($response['permalink']) . "</link> \n";
            $return .= "\t\t\t<description>" . $this->xml_safe($response['expert']) . "</description> \n";
            $return .= "\t\t</item> \n";
            $return .= "\t</channel> \n";
            $return .= "\t</rss> \n";
        } else {
            // Something went wrong - provide reason from $response (string)...
            $return .= "\t<error>1</error> \n";
            $return .= "\t<message>" . $this->xml_safe($response) . "</message>\n";
        } 
        // End response to trackbacker
        $return .= "</response>";

        return $return;
    } 

    /**
     * Produces embedded RDF representing metadata about the entry,
     * allowing clients to auto-discover the TrackBack Ping URL.
     * 
     * NOTE: DATE should be string in RFC822 Format - Use RFC822_from_datetime().
     * 
     * <code><?php
     * include('trackback.php');
     * $trackback = new PingtraxTrackback('Chronolabs Cooperative', 'Simon Antony Roberts', 'UTF-8');
     * 
     * echo $trackback->rdf_autodiscover(string DATE, string TITLE, string EXPERT, string PERMALINK, string TRACKBACK [, string AUTHOR]);
     * ?></code>
     * 
     * @param string $RFC822_date 
     * @param string $title 
     * @param string $expert 
     * @param string $permalink 
     * @param string $trackback 
     * @param string $author 
     * @return string 
     */
    function rdf_autodiscover($RFC822_date, $title, $expert, $permalink, $trackback, $author = "")
    {
        if (!$author) {
            $author = $this->author;
        } 

        $return = "\n\n<!--\n";
        $return .= "<rdf:RDF xmlns:rdf=\"http://www.w3.org/1999/02/22-rdf-syntax-ns#\" \n";
        $return .= "\txmlns:dc=\"http://purl.org/dc/elements/1.1/\" \n";
        $return .= "\txmlns:trackback=\"http://madskills.com/public/xml/rss/module/trackback/\"> \n";
        $return .= "<rdf:Description \n";
        $return .= "\trdf:about=\"" . $this->xml_safe($permalink) . "\" \n";
        $return .= "\tdc:identifier=\"" . $this->xml_safe($permalink) . "\" \n";
        $return .= "\ttrackback:ping=\"" . $this->xml_safe($trackback) . "\" \n";
        $return .= "\tdc:title=\"" . $this->xml_safe($title) . "\" \n";
        $return .= "\tdc:subject=\"TrackBack\" \n";
        $return .= "\tdc:description=\"" . $this->xml_safe($this->cut_short($expert)) . "\" \n";
        $return .= "\tdc:creator=\"" . $this->xml_safe($author) . "\" \n";
        $return .= "\tdc:date=\"" . $RFC822_date . "\" /> \n";
        $return .= "</rdf:RDF> \n";
        $return .= "-->\n";

        return $return;
    } 

    /**
     * Search text for links, and searches links for trackback URLs.
     * 
     * <code><?php
     * 
     * include('trackback.php');
     * $trackback = new PingtraxTrackback('Chronolabs Cooperative', 'Simon Antony Roberts', 'UTF-8');
     * 
     * if ($trackback_array = $trackback->auto_discovery(string TEXT)) {
     * 	// Found trackbacks in TEXT. Looping...
     * 	foreach($trackback_array as $trackback_key => $trackback_url) {
     * 	// Attempt to ping each one...
     * 		if ($trackback->ping($trackback_url, string URL, [string TITLE], [string EXPERT])) {
     * 			// Successful ping...
     * 			echo "PingtraxTrackback sent to <i>$trackback_url</i>...\n";
     * 		} else {
     * 			// Error pinging...
     * 			echo "PingtraxTrackback to <i>$trackback_url</i> failed....\n";
     * 		}
     * 	}
     * } else {
     * 	// No trackbacks in TEXT...
     * 	echo "No trackbacks were auto-discover...\n"
     * }
     * ?></code>
     * 
     * @param string $text 
     * @return array PingtraxTrackback URLs.
     */
    function auto_discovery($text)
    { 
        // Get a list of UNIQUE links from text...
        // ---------------------------------------
        // RegExp to look for (0=>link, 4=>host in 'replace')
        $reg_exp = "/(http)+(s)?:(\\/\\/)((\\w|\\.)+)(\\/)?(\\S+)?/i"; 
        // Make sure each link ends with [sapce]
        $text = eregi_replace("www.", "http://www.", $text);
        $text = eregi_replace("http://http://", "http://", $text);
        $text = eregi_replace("\"", " \"", $text);
        $text = eregi_replace("'", " '", $text);
        $text = eregi_replace(">", " >", $text); 
        // Create an array with unique links
        $uri_array = array();
        if (preg_match_all($reg_exp, strip_tags($text, "<a>"), $array, PREG_PATTERN_ORDER)) {
            foreach($array[0] as $key => $link) {
                foreach((array(",", ".", ":", ";")) as $t_key => $t_value) {
                    $link = trim($link, $t_value);
                } 
                $uri_array[] = ($link);
            } 
            $uri_array = array_unique($uri_array);
        } 
        // Get the trackback URIs from those links...
        // ------------------------------------------
        // Loop through the URIs array and extract RDF segments
        $rdf_array = array(); // <- holds list of RDF segments
        foreach($uri_array as $key => $link) {
            if ($link_content = implode("", @file($link))) {
                preg_match_all('/(<rdf:RDF.*?<\/rdf:RDF>)/sm', $link_content, $link_rdf, PREG_SET_ORDER);
                for ($i = 0; $i < count($link_rdf); $i++) {
                    if (preg_match('|dc:identifier="' . preg_quote($link) . '"|ms', $link_rdf[$i][1])) {
                        $rdf_array[] = trim($link_rdf[$i][1]);
                    } 
                } 
            } 
        } 
        // Loop through the RDFs array and extract trackback URIs
        $trackback_array = array(); // <- holds list of trackback URIs
        if (!empty($rdf_array)) {
            for ($i = 0; $i < count($rdf_array); $i++) {
                if (preg_match('/trackback:ping="([^"]+)"/', $rdf_array[$i], $array)) {
                    $trackback_array[] = trim($array[1]);
                } 
            } 
        } 
        // Return Trackbacks
        return $trackback_array;
    } 

    /**
     * Other Useful functions used in this class
     */

    /**
     * Converts MySQL datetime to a standart RFC 822 date format
     * 
     * @param string $datetime 
     * @return string RFC 822 date
     */
    function RFC822_from_datetime($datetime)
    {
        $timestamp = mktime(
            substr($datetime, 8, 2), substr($datetime, 10, 2), substr($datetime, 12, 2),
            substr($datetime, 4, 2), substr($datetime, 6, 2), substr($datetime, 0, 4)
            );
        return date("r", $timestamp);
    } 

    /**
     * Converts a string into an XML-safe string (replaces &, <, >, " and ')
     * 
     * @param string $string 
     * @return string 
     */
    function xml_safe($string)
    {
        return htmlspecialchars($string, ENT_QUOTES);
    } 

    /**
     * Cuts a string short (with "...") accroding to $max_length...
     * 
     * @param string $string 
     * @param integer $max_length 
     * @return string 
     */
    function cut_short($string, $max_length = 255)
    {
        if (strlen($string) > $max_length) {
            $string = substr($string, 0, $max_length) . '...';
        } 

        return $string;
    } 
} 

?>