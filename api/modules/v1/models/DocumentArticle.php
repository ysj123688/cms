<?php

	namespace api\modules\v1\models;

	use api\modules\v1\models\Base;

	class DocumentArticle extends Base {

		public static function tableName()
		{
			return 'cms_document_article';
		}

	}