<?php
//
// sqlite3_accessor.php
// Created by Yamada Kenta
// Copyright (c) 2017 yamaken. All right reserved.
//

class SQLite3Accessor extends SQLite3
{
    const DATABASE_PATH = "/path/to/";
    private $db_path;

    //
    // コンストラクタ
    // @params
    //  $dbname String: データベース名(グローバル定数の中から指定)
    //
    public function __construct($dbname) {
        $this->db_path = SQLite3Accessor::DATABASE_PATH.$dbname;
        parent::__construct($this->db_path, SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE);
        parent::enableExceptions(TRUE);
    }


    //
    // 複数のSQL(Create, Insert, Update, Delete)を順番に実行
    // @params
    //  $queries Array of String: 複数のSQL文
    // @return Boolean
    //  True: 処理成功 / False: 失敗
    // クエリ失敗時のメッセージの確認は「lastErrorMsg」をコールして下さい。
    //
    public function execute_many($queries) {
        foreach($queries as $q) {
            if(!$this->exec($q)) {
                return FALSE;
            }
        }
        return TRUE;
    }

    //
    // トランザクション開始
    // @params
    //  $isolation_level String: トランザクション分離レベル(初期値はDEFERED)
    //
    public function begin_transaction($isolation_level="DEFERED") {
        parent::exec("BEGIN $isolation_level;");
    }

    //
    // コミット
    // このメソッドを使用する前に必ず「begin_transaction」をコールすること
    //
    public function commit() {
        parent::exec("COMMIT");
    }

    //
    // ロールバック
    // このメソッドを使用する前に必ず「begin_transaction」をコールすること
    //
    public function rollback() {
        parent::exec("ROLLBACK;");
    }
}

?>
