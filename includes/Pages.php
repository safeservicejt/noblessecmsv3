<?php

class Pages
{

	public static function get($inputData=array())
	{
		Table::setTable('pages');

		Table::setFields('id,userid,title,friendly_url,date_added,status,content,page_title,descriptions,keywords,type,allowcomment,views,image');

		$result=Table::get($inputData,function($rows,$inputData){

			$total=count($rows);

			for ($i=0; $i < $total; $i++) { 

				if(isset($rows[$i]['friendly_url']))
				{
					$rows[$i]['url']=System::getUrl().'pages/'.$rows[$i]['friendly_url'].'.html';
				}

				if(isset($rows[$i]['title']))
				{
					$rows[$i]['title']=stripslashes($rows[$i]['title']);

					$rows[$i]['title']=Render::runTableContentProcess('pages','title',$rows[$i]['title']);
				}

				if(isset($rows[$i]['page_title']))
				{
					$rows[$i]['page_title']=stripslashes($rows[$i]['page_title']);

					$rows[$i]['page_title']=Render::runTableContentProcess('pages','page_title',$rows[$i]['page_title']);
				}

				if(isset($rows[$i]['descriptions']))
				{
					$rows[$i]['descriptions']=stripslashes($rows[$i]['descriptions']);

					$rows[$i]['descriptions']=Render::runTableContentProcess('pages','descriptions',$rows[$i]['descriptions']);
				}

				if(isset($rows[$i]['keywords']))
				{
					$rows[$i]['keywords']=stripslashes($rows[$i]['keywords']);

					$rows[$i]['keywords']=Render::runTableContentProcess('pages','keywords',$rows[$i]['keywords']);
				}

				if(isset($rows[$i]['image']))
				{
					$rows[$i]['image']=Render::runTableContentProcess('pages','image',$rows[$i]['image']);

					$rows[$i]['imageUrl']=System::getUrl().$rows[$i]['image'];
				}

				if(isset($rows[$i]['content']))
				{
					$rows[$i]['content']=stripslashes($rows[$i]['content']);
					
					if($inputData['isHook']=='yes')
					{
						$rows[$i]['content']=Shortcode::render($rows[$i]['content']);
					}

					$rows[$i]['content']=Render::runTableContentProcess('pages','content',$rows[$i]['content']);
				}



			}

			return $rows;

		});

		return $result;
	}

	public static function insert($inputData=array())
	{
		Table::setTable('pages');

		$result=Table::insert($inputData,function($inputData){
			if(!isset($inputData['userid']))
			{
				$inputData['userid']=Users::$id;
			}

			if(!isset($inputData['date_added']))
			{
				$inputData['date_added']=date('Y-m-d H:i:s');
			}

			if(isset($inputData['title']))
			{
				$inputData['title']=addslashes($inputData['title']);
			}
			if(isset($inputData['content']))
			{
				$inputData['content']=addslashes($inputData['content']);
			}

			if(!isset($inputData['page_title']) || !isset($inputData['page_title'][5]))
			{
				$inputData['page_title']=$inputData['title'];
			}

			if(isset($inputData['descriptions']))
			{
				$inputData['descriptions']=addslashes($inputData['descriptions']);
			}

			if(isset($inputData['page_title']))
			{
				$inputData['page_title']=addslashes($inputData['page_title']);
			}

			if(isset($inputData['keywords']))
			{
				$inputData['keywords']=addslashes($inputData['keywords']);
			}
			
			return $inputData;

		},function($inputData){
			if(isset($inputData['id']))
			{
				self::update($inputData['id'],array(
					'friendly_url'=>Strings::makeFriendlyUrl(strip_tags($inputData['title'])).'-'.$inputData['id']
					));
			}
		});

		self::saveCache($result);

		return $result;
	}

	public static function update($listID,$updateData=array())
	{
		Table::setTable('pages');

		$result=Table::update($listID,$updateData,function($inputData){
			if(isset($inputData['title']))
			{
				$inputData['title']=addslashes($inputData['title']);
			}

			if(isset($inputData['page_title']))
			{
				$inputData['page_title']=addslashes($inputData['page_title']);
			}

			if(isset($inputData['descriptions']))
			{
				$inputData['descriptions']=addslashes($inputData['descriptions']);
			}

			if(isset($inputData['keywords']))
			{
				$inputData['keywords']=addslashes($inputData['keywords']);
			}

			if(isset($inputData['content']))
			{
				$inputData['content']=addslashes($inputData['content']);
			}

			return $inputData;

		});

		self::saveCache($listID);

		return $result;
	}

	public static function remove($inputIDs=array(),$whereQuery='')
	{
		Table::setTable('pages');

		$result=Table::remove($inputIDs,$whereQuery);

		self::removeCache($inputIDs);

		return $result;
	}


	public static function exists($id)
	{
		Table::setTable('pages');

		$result=Table::exists($id);

		return $result;
	}

	public static function loadCache($id)
	{
		Table::setTable('pages');

		$result=Table::loadCache($id,function($id){
			Pages::saveCache($id);
		});

		if(isset($result['friendly_url']))
		{
			$result['url']=System::getUrl().'pages/'.$result['friendly_url'].'.html';
		}

		if(isset($result['title']))
		{
			$result['title']=stripslashes($result['title']);

			$result['title']=Render::runTableContentProcess('pages','title',$result['title']);
		}

		if(isset($result['page_title']))
		{
			$result['page_title']=stripslashes($result['page_title']);

			$result['page_title']=Render::runTableContentProcess('pages','page_title',$result['page_title']);
		}

		if(isset($result['descriptions']))
		{
			$result['descriptions']=stripslashes($result['descriptions']);

			$result['descriptions']=Render::runTableContentProcess('pages','descriptions',$result['descriptions']);
		}

		if(isset($result['keywords']))
		{
			$result['keywords']=stripslashes($result['keywords']);

			$result['keywords']=Render::runTableContentProcess('pages','keywords',$result['keywords']);
		}

		if(isset($result['image']))
		{
			$result['image']=Render::runTableContentProcess('pages','image',$result['image']);

			$result['imageUrl']=System::getUrl().$result['image'];
		}

		if(isset($result['content']))
		{
			$result['content']=stripslashes($result['content']);
			
			$result['content']=Shortcode::render($result['content']);

			$result['content']=Render::runTableContentProcess('pages','content',$result['content']);
		}		

		return $result;
	}

	public static function removeCache($id)
	{
		Table::setTable('pages');

		Table::removeCache($id);

	}

	public static function saveCache($id)
	{
		Table::setTable('pages');

		Table::saveCache($id);
	}
}