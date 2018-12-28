<?php
class SupsysticTables_Woocommerce_Model_Woocolumns extends SupsysticTables_Core_BaseModel
{
	/**
	 * For this model is important to create the mirror functions in SupsysticTables_Tables_Model_Tables
	 * @see SupsysticTables_Tables_Model_Tables::getAllTableHistory and ets.
	 */

	public function getAllWooColumns()
	{
		$query = $this->getQueryBuilder()
			->select('*')
			->from($this->getTable('woo_columns'));

		$WooColumns = $this->db->get_results($query->build());

		return $WooColumns;
	}

}