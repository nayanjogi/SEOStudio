<?php

	class ToolManager {
		private $data;
		private $tools;

		function setDatabase($dbResource) {
			$this->data = $dbResource;
			return true;
		}
		function setupTools() {
			$tools = array(
				'Search Engine Ranking' => array(
					array("Check Backlinks", "resources/icons/check-backlinks.png", "Find the approximate number of backlinks to a webpage."),
					array("Compare Backlinks", "resources/icons/compare-backlinks.png", "Compare the number of backlinks between multiple websites."),
					array("Predict Backlinks", "resources/icons/predict-backlinks.png", "Predict the future backlinks for a webpage."),
					array("Check Competition", "resources/icons/check-competition.png", "Find the number of competing websites for a webpage's position on search engines."),
					array("Estimate PageRank", "resources/icons/estimate-pagerank.png", "Estimate the current Google PageRank of a website."),
					array("Compare PageRank", "resources/icons/compare-pagerank.png", "Compare the PageRanks of multiple websites."),
					array("Predict PageRank", "resources/icons/predict-pagerank.png", "Predict the future PageRank of a webpage."),
					array("EDU Backlinks", "resources/icons/edu-backlinks.png", "Find the number of .edu backlinks."),
					array("GOV Backlinks", "resources/icons/gov-backlinks.png", "Find the number of .gov backlinks."),
					array("Google SERP Position", "resources/icons/google-keyword-position.png", "Find your website rank for a specific keyword in google search."),
					array("Keyword Density", "resources/icons/keyword-density.png", "Determine how often you use certain words on your website.")
				),
				'Robot Resources' => array(
					array("Generate Keywords", "resources/icons/generate-keywords.png", "Easily find new keywords for your website."),
					array("Clean Keywords", "resources/icons/clean-keywords.png", "Optimize and clean a list of keywords"),
					array("Extract Meta Tags", "resources/icons/extract-meta-tags.png", "Extract HTML meta tags from a webpage."),
					array("Generate Meta Tags", "resources/icons/generate-meta-tags.png", "Generate HTML meta tags for a webpage."),
					array("Generate Robots.txt", "resources/icons/generate-robots.txt.png", "Generate a robots.txt file for a website."),
					array("Generate Sitemap", "resources/icons/generate-sitemap.png", "Generate a sitemap.xml file for a website.")
				),
				'Website Compatibility' => array(
					array("Website Speed Test", "resources/icons/website-speed-test.png", "Test the download speed for a webpage and all of its resources."),
					array("My Browser Details", "resources/icons/my-browser-details.png", "Find the details for your current browser."),
					array("Responsive Check", "resources/icons/responsive-check.png", "Check a website to see if it's responsive."),
					array("Check Headers", "resources/icons/check-headers.png", "Find the HTTP headers for a webpage.")
				),
				'Behind The Scenes' => array(
					array("Obfuscate Email", "resources/icons/obfuscate-email.png", "Convert a plain-text email address into code so spammer robots can't see it."),
					array("Analyze Links", "resources/icons/analyze-links.png", "View the details for all of the links on a webpage."),
					array("Get Source Code", "resources/icons/get-source-code.png", "View the highlighted source code of a webpage."),
					array("Get Webpage Size", "resources/icons/get-webpage-size.png", "View the download size of a webpage.")
				),
				'DNS and Internet' => array(
					array("Ping Test", "resources/icons/ping-test.png", "Test the ping of a website (how long it takes to connect)."),
					array("My IP Address", "resources/icons/my-ip-address.png", "Find your current public IP address."),
					array("Website IP Address", "resources/icons/website-ip-address.png", "Find the IP address of a website's server."),
					array("Spider Preview", "resources/icons/spider-preview.png", "View a website in the same way a spider or crawler would view it.")
				),
				'Domain Investigation' => array(
					array("Whois Lookup", "resources/icons/whois-lookup.png", "Perform a Whois Lookup on a domain name."),
					array("Indexed Pages Lookup", "resources/icons/indexed-pages-lookup.png", "Find the number of indexed pages for a website."),
					array("Blog Backlinks Lookup", "resources/icons/blog-backlinks-lookup.png", "Find the number of blogs that link to your website."),
					array("Blacklist Lookup", "resources/icons/blacklist-lookup.png", "Check to see if your website's IP address is blacklisted.")
				)
			);
			
			$this->data->write("Tools", $tools);
			$this->data->save();
		}
		function init() {

			$dl = $this->data->read("Tools");
			if(!isset($dl) || $dl == false) {
				$this->setupTools();
			}

			$this->tools = $this->data->read("Tools");
			
			$firstIndex = '';
			foreach($this->tools as $i=>$v) {
				if($firstIndex === '') {
					$firstIndex = $i;
				}
			}
			
			if(file_exists("tools/seo-report-generator") || file_exists("../tools/seo-report-generator")) {
				$this->tools[$firstIndex][] = array("Generate SEO Report", "resources/icons/analyze-links.png", "Generate an SEO report card for your website.");
			}

			return true;
		}

		function getToolData() {
			return $this->tools;
		}

	}

?>
