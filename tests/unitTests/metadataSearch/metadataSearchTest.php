<?php

require_once(dirname(__FILE__) . '/../../bootstrap/bootstrapApi.php');

/**
 * The KMC sanity test case
 * tests if decision layer makes a right decision about converting and validating files 
 * @author Roni
 *
 * TODO: change the file name to be caps
 */
class metadataSearchTest extends KalturaApiTestCase
{
	/**
	 * 
	 * Creates a new KMC Test case
	 * @param string $name
	 * @param array<unknown_type> $data
	 * @param string $dataName
	 */
	public function __construct($name = "metadataSearchTest", array $data = array(), $dataName ="Default data")
	{
		parent::__construct($name, $data, $dataName);
	}
	
	/**
	 * 
	 * Test the KMC Checks that the starting calls return okay
	 * @param array<unknown_type> $params
	 * @param array<unknown_type> $results
	 * @dataProvider provideData
	 */
	public function testMetadataSearch($metadataProfileId, $fieldName1, $fieldValue1, $fieldName2, $fieldValue2,  $fieldName3, $expectedEntries)
	{		
		$searchCondition1 = new KalturaSearchCondition();
		$searchCondition1->field = $fieldName1;
		$searchCondition1->value = $fieldValue1;
		
		$searchCondition2 = new KalturaSearchCondition();
		$searchCondition2->field = $fieldName2;
		$searchCondition2->value = $fieldValue2;
		
		$searchItems = array();
		$searchItems[] = $searchCondition1;
		$searchItems[] = $searchCondition2;
		
		$metadataSearchItem = new KalturaMetadataSearchItem();
		$metadataSearchItem->type = KalturaSearchOperatorType::SEARCH_OR;
		$metadataSearchItem->metadataProfileId = $metadataProfileId;
		$metadataSearchItem->items = $searchItems;
		$metadataSearchItem->orderBy = '+' .$fieldName3;
		
		$filter = new KalturaMediaEntryFilter();
		$filter->advancedSearch = $metadataSearchItem;
		
		$entries = $this->client->media->listAction($filter);
		$i = 0;
		
		foreach ($entries as $entry)
		{
			$i++;
			$this->CompareAPIObjects($expectedEntries[$i], $entry);	
		}
		
	}
}