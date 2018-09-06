<?php

	class Document{
		public $Id;
		public $IdFolder;
		public $Name;
		public $Markdown;
	}

	class Folder{
		public $Id;
		public $IdUser;
		public $Name;
		public $Documents;
	}

?>