<?php
class Db
{
	private $_numRow;
	private $dbh = null;

	public function __construct()
	{
		$driver = "mysql:host=" . HOST . "; dbname=" . DB_NAME;
		try {
			$this->dbh = new PDO($driver, DB_USER, DB_PASS);
			$this->dbh->query("set names 'utf8' ");

		} catch (PDOException $e) {
			echo "Err:" . $e->getMessage();
			exit();
		}
	}

	public function __destruct()
	{
		$this->dbh = null;
	}

	public function getRowCount()
	{
		return $this->_numRow;
	}

	private function query($sql, $arr = array(), $mode = PDO::FETCH_ASSOC)
	{
		$stm = $this->dbh->prepare($sql);
		if (!$stm->execute($arr)) {
			return null;
		}
		$this->_numRow = $stm->rowCount();
		return $stm->fetchAll($mode);

	}

	public function select($sql, $arr = array(), $mode = PDO::FETCH_ASSOC)
	{
		return $this->query($sql, $arr, $mode);
	}

	public function insert($sql, $arr = array(), $mode = PDO::FETCH_ASSOC)
	{
		$this->query($sql, $arr, $mode);
		$lastInsertId = $this->dbh->lastInsertId();
		$rowCount = $this->getRowCount();
		return ['rowCount' => $rowCount, 'lastInsertId' => $lastInsertId];
	}

	public function update($sql, $arr = array(), $mode = PDO::FETCH_ASSOC)
	{
		$this->query($sql, $arr, $mode);
		return ['rowCount' => $this->getRowCount()];
	}

	public function delete($sql, $arr = array(), $mode = PDO::FETCH_ASSOC)
	{
		$this->query($sql, $arr, $mode);
		return ['rowCount' => $this->getRowCount()];
	}


}
?>