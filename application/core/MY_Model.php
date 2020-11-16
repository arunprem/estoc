<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * MY_Model done all nestedset operations 
 *
 * @author admin
 */
class MY_Model extends CI_Model {

    public $nested_id;
    public $table_name;
    public $asset_id;
    public $parent_id = NULL;
    public $root_id;
    public $lft;
    public $rgt;
    public $hlevel;
    public $table = "nestedset";
    ///////////datatble vars/////////////

    var $post_search;
    var $post_order;
    var $post_length;
    var $post_start;
    var $post_draw;

    /////////////////////////////////////

    public function __construct($table_name = NULL) {
        parent::__construct();
        $this->table_name = $table_name;
    }

    // Create root for different tables and different hierarchies

    public function createRoot() {

        $data = array(
            'table_name' => $this->table_name,
            'asset_id' => $this->asset_id,
            'root_id' => $this->root_id,
            'parent_id' => 0,
            'lft' => 1,
            'rgt' => 2,
            'hlevel' => 0
        );
        $this->db->insert($this->table, $data);
    }

    // Get node by asset id and table name
    private function getNodeById() {
        $this->db->select(' nested_id,         
                            table_name, 
                            asset_id,   
                            parent_id,  
                            root_id,    
                            lft,        
                            rgt,        
                            hlevel');

        $where = array(
            'table_name' => $this->table_name,
            'asset_id' => $this->asset_id
        );

        $result = $this->db->get_where($this->table, $where);

        if ($node = $result->row()) {
            $this->asset_id = $node->asset_id;
            $this->hlevel = $node->hlevel;
            $this->lft = $node->lft;
            $this->rgt = $node->rgt;
            $this->root_id = $node->root_id;
            $this->parent_id = $node->parent_id;
            return $node;
        } else {
            return false;
        }
    }

    public function getNode($id) {
        $sql = "SELECT
		nested_id,         
		table_name, 
		asset_id,   
                parent_id,  
		root_id,    
		lft,        
                rgt,        
		hlevel FROM " . $this->db->escape_str($this->table) . "  
		WHERE table_name=" . $this->db->escape($this->table_name) . " 
		AND asset_id =" . $this->db->escape($id) . "";
        //echo $sql;

        $result = $this->db->query($sql);

        if ($node = $result->row()) {
            return $node;
        }
    }

    public function createNode() {
        //Create a new node in a tree			
        if (!$this->getNodeById()) {

            if ($this->parent_id == NULL) {
                $this->createRoot();
                return $this->db->insert_id();
            }

            $pnode = new MY_Model($this->table_name);
            $pnode->asset_id = $this->parent_id;
            $parent = $pnode->getNodeById();

            $sql = "UPDATE " . $this->db->escape_str($this->table) . " SET rgt = (rgt + 2) 
                    WHERE 
                    rgt >= " . $this->db->escape($parent->rgt) . " AND 
                    root_id = " . $this->db->escape($parent->root_id) . " AND
                    table_name = " . $this->db->escape($parent->table_name) . "";
            //echo $sql;

            $this->db->query($sql);

            $sql = "UPDATE " . $this->db->escape_str($this->table) . " SET lft = (lft + 2) 
                    WHERE 
                    lft > " . $this->db->escape($parent->rgt) . " AND 
                    root_id = " . $this->db->escape($parent->root_id) . " AND
                    table_name = " . $this->db->escape($parent->table_name) . "";

            $this->db->query($sql);

            $sql = "INSERT INTO " . $this->db->escape_str($this->table) . " 
                    (table_name, asset_id, parent_id, root_id, lft, rgt, hlevel)VALUES
                    (" . $this->db->escape($this->table_name) . ", " . $this->db->escape($this->asset_id) . ", " . $this->db->escape($parent->asset_id) . ", 
                    " . $this->db->escape($parent->root_id) . ", " . $parent->rgt . ", " . ($parent->rgt + 1) . ", 
                    " . ($parent->hlevel + 1) . " )";


            $this->db->query($sql);
            return true;
        } else {
            return false;
        }
    }

    // Get node right to the current node
    public function getRgtNode() {

        if (!$this->rgt) {
            $this->getNodeById();
        }
        $sql = "SELECT
		nested_id,         
		table_name, 
		asset_id,   
		parent_id,  
		root_id,    
		lft,        
		rgt,        
		hlevel FROM " . $this->db->escape_str($this->table) . " 
		WHERE 
		lft = " . ($this->rgt + 1) . "
		AND table_name=" . $this->db->escape($this->table_name) . "
		AND root_id =" . $this->db->escape($this->root_id) . "";
        $result = $this->db->query($sql);

        if ($node = $result->row()) {
            return $node;
        } else {
            return false;
        }
    }

    // Get node left to the current node
    public function getLftNode() {

        if (!$this->lft) {
            $this->getNodeById();
        }

        $sql = "SELECT
		nested_id,         
		table_name, 
		asset_id,   
		parent_id,  
		root_id,    
		lft,        
		rgt,        
		hlevel FROM " . $this->db->escape_str($this->table) . " 
		WHERE 
		rgt = " . ($this->lft - 1) . "
		AND table_name=" . $this->db->escape($this->table_name) . "
		AND root_id =" . ($this->root_id) . "";
        $result = $this->db->query($sql);

        if ($node = $result->row()) {
            return $node;
        } else {
            return false;
        }
    }

    //Insert a node before the asset id supplied in same level and under same parent.
    public function insertLeft($rgt_assetid) {

        if (!$this->getNodeById()) {
            $rgtnode = new MY_Model($this->table_name);
            $rgtnode->asset_id = $rgt_assetid;

            if ($rgtnode->getNodeById()) {

                $sql = "UPDATE " . $this->db->escape_str($this->table) . " SET rgt = (rgt + 2) 
			WHERE 
			rgt > " . $rgtnode->lft . " AND 
			root_id = " . $rgtnode->root_id . " AND
			table_name = " . $this->db->escape($this->table_name) . "";

                $this->db->query($sql);

                $sql = "UPDATE " . $this->db->escape_str($this->table) . " SET lft = (lft + 2) 
			WHERE 
			lft >= " . $rgtnode->lft . " AND 
			root_id = " . $rgtnode->root_id . " AND
			table_name = " . $this->db->escape($this->table_name) . "";

                $this->db->query($sql);

                $sql = "INSERT INTO " . $this->db->escape_str($this->table) . " 
			(table_name, asset_id, parent_id, root_id, lft, rgt, hlevel)VALUES
			(" . $this->db->escape($this->table_name) . ", " . $this->db->escape($this->asset_id) . ", " . $rgtnode->parent_id . ", 
			" . $rgtnode->root_id . ", " . ($rgtnode->lft) . ", " . ($rgtnode->lft + 1) . ", 
			" . ($rgtnode->hlevel) . " )";


                $this->db->query($sql);
                return $this->db->insert_id();
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    //Insert a node after the asset id supplied in same level and under same parent.
    public function insertRight($lft_assetid) {

        if (!$this->getNodeById()) {

            $lftnode = new MY_Model($this->table_name);
            $lftnode->asset_id = $lft_assetid;

            if ($lftnode->getNodeById()) {

                $sql = "UPDATE " . $this->db->escape_str($this->table) . " SET rgt = (rgt + 2) 
			WHERE 
			rgt > " . $lftnode->rgt . " AND 
			root_id = " . $lftnode->root_id . " AND
			table_name = " . $this->db->escape($this->table_name) . "";

                $this->db->query($sql);

                $sql = "UPDATE " . $this->db->escape_str($this->table) . " SET lft = (lft + 2) 
			WHERE 
			lft > " . $lftnode->rgt . " AND 
			root_id = " . $lftnode->root_id . " AND
			table_name = " . $this->db->escape($this->table_name) . "";

                $this->db->query($sql);

                $sql = "INSERT INTO " . $this->db->escape_str($this->table) . " 
			(table_name, asset_id, parent_id, root_id, lft, rgt, hlevel)VALUES
			(" . $this->db->escape($this->table_name) . ", " . $this->db->escape($this->asset_id) . ", " . $lftnode->parent_id . ", 
			" . $lftnode->root_id . ", " . ($lftnode->rgt + 1) . ", " . ($lftnode->rgt + 2) . ", 
			" . ($lftnode->hlevel) . " )";

                $this->db->query($sql);
                return $this->db->insert_id();
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    //Move node one step left

    public function moveLeft() {
        if ($this->getNodeById()) {

            if ($leftnode = $this->getLftNode()) {
                $nodesize = $this->rgt - $this->lft + 1;
                $lnodesize = $leftnode->rgt - $leftnode->lft + 1;

                //Negate the left and right of leftnode 
                $sql = "UPDATE " . $this->db->escape_str($this->table) . "
			SET lft = lft * (-1), 
			rgt = rgt * (-1)
			WHERE 
			lft >= " . $leftnode->lft . " 
			AND rgt <= " . $leftnode->rgt . "
			AND root_id = " . $this->root_id . "
			AND table_name = " . $this->db->escape($this->table_name) . ";";
                //echo $sql."<br>";
                $this->db->query($sql);


                // Move node to left

                $sql = "UPDATE " . $this->db->escape_str($this->table) . "
			SET lft = lft-" . $lnodesize . ",
			rgt = rgt-" . $lnodesize . "
			WHERE 
			lft >= " . $this->lft . " 
			AND rgt <= " . $this->rgt . "
			AND root_id = " . $this->root_id . "
			AND table_name = " . $this->db->escape($this->table_name) . ";";

                //echo $sql."<br>";
                $this->db->query($sql);


                //Move leftnode to right 

                $sql = "UPDATE " . $this->db->escape_str($this->table) . "
			SET lft = (lft-" . $nodesize . ") * (-1), 
			rgt = (rgt-" . $nodesize . ") * (-1)
			WHERE 
			lft <= " . $leftnode->lft . "*(-1) 
			AND rgt >= " . $leftnode->rgt . "*(-1)
			AND root_id = " . $this->root_id . "
			AND table_name = " . $this->db->escape($this->table_name) . ";";

                //echo $sql."<br>";
                $this->db->query($sql);

                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    //Move node one step right

    public function moveRight() {
        if ($this->getNodeById()) {

            if ($rightnode = $this->getRgtNode()) {
                $nodesize = $this->rgt - $this->lft + 1;
                $rnodesize = $rightnode->rgt - $rightnode->lft + 1;

                //Negate the left and right of rightnode 
                $sql = "UPDATE " . $this->db->escape_str($this->table) . "
			SET lft = lft * (-1), 
			rgt = rgt * (-1)
			WHERE 
			lft >= " . $rightnode->lft . " 
			AND rgt <= " . $rightnode->rgt . "
			AND root_id = " . $this->root_id . "
			AND table_name = " . $this->db->escape($this->table_name) . ";";

                $this->db->query($sql);


                // Move node to right

                $sql = "UPDATE " . $this->db->escape_str($this->table) . "
			SET lft = lft+" . $rnodesize . ",
			rgt = rgt+" . $rnodesize . "
			WHERE 
			lft >= " . $this->lft . " 
			AND rgt <= " . $this->rgt . "
			AND root_id = " . $this->root_id . "
			AND table_name = " . $this->db->escape($this->table_name) . ";";

                $this->db->query($sql);


                //Move rightnode to left 

                $sql = "UPDATE " . $this->db->escape_str($this->table) . "
			SET lft = (lft+" . $nodesize . ") * (-1), 
			rgt = (rgt+" . $nodesize . ") * (-1)
			WHERE 
			lft <= " . $rightnode->lft . "*(-1) 
			AND rgt >= " . $rightnode->rgt . "*(-1)
			AND root_id = " . $this->root_id . "
			AND table_name = " . $this->db->escape($this->table_name) . ";";

                $this->db->query($sql);

                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    // Move to new Parent
    public function moveTo($new_parent_id) {

        if ($this->getNodeById()) {

            if ($this->asset_id == $new_parent_id) {
                return false;
            }

            $new_parent = new MY_Model($this->table_name);
            $new_parent->asset_id = $new_parent_id;
            $new_parent->getNodeById();

            $nodesize = $this->rgt - $this->lft + 1;


            //negate current node and children
            $sql = "UPDATE " . $this->db->escape_str($this->table) . "
                    SET lft = ((lft - " . $this->lft . ") + 1) * (-1), 
                    rgt = ((rgt - " . $this->lft . ") + 1) * (-1),
                    hlevel = (hlevel -" . $this->hlevel . ")+1
                    WHERE 
                    lft >= " . $this->lft . " 
                    AND rgt <= " . $this->rgt . "
                    AND root_id = " . $this->root_id . "
                    AND table_name = " . $this->db->escape($this->table_name) . ";";
            //echo "<br>".$sql;
            $this->db->query($sql);


            //update tree 
            $sql = "UPDATE " . $this->db->escape_str($this->table) . " SET
                    lft = lft - " . $nodesize . "
                    WHERE
                    lft > " . $this->rgt . "  
                    AND root_id = " . $this->root_id . "
                    AND table_name = " . $this->db->escape($this->table_name) . ";";

            //echo "<br>".$sql;
            $this->db->query($sql);

            //update tree 
            $sql = "UPDATE " . $this->db->escape_str($this->table) . " SET
                    rgt = rgt - " . $nodesize . "
                    WHERE
                    rgt > " . $this->rgt . "  
                    AND root_id = " . $this->root_id . "
                    AND table_name = " . $this->db->escape($this->table_name) . ";";

            //echo "<br>".$sql;
            $this->db->query($sql);


            // Update parent id
            $sql = "UPDATE " . $this->db->escape_str($this->table) . " SET
                    parent_id = " . $new_parent_id . "
                    WHERE 
                    asset_id = " . $this->db->escape($this->asset_id) . " 
                    AND root_id = " . $this->root_id . "
                    AND table_name = " . $this->db->escape($this->table_name) . ";";


            //echo "<br>".$sql;
            $this->db->query($sql);


            // Update tree 
            $updatedtree = new MY_Model($this->table_name);
            $updatedtree->asset_id = $new_parent_id;
            $updatedtree->getNodeById();

            //update tree for isertion
            $sql = "UPDATE " . $this->db->escape_str($this->table) . " SET
                    lft = lft + " . $nodesize . " 
                    WHERE
                    lft > " . $updatedtree->lft . " 
                    AND root_id = " . $this->root_id . "
                    AND table_name = " . $this->db->escape($this->table_name) . ";";

            //echo "<br>".$sql;
            $this->db->query($sql);


            $sql = "UPDATE " . $this->db->escape_str($this->table) . " SET
                    rgt = rgt + " . $nodesize . " 
                    WHERE
                    rgt > " . $updatedtree->lft . " 
                    AND root_id = " . $this->root_id . "
                    AND table_name = " . $this->db->escape($this->table_name) . ";";

            //echo "<br>".$sql;
            $this->db->query($sql);

            //update pluged nodes
            $sql = "UPDATE " . $this->db->escape_str($this->table) . " SET
                    lft = (lft *(-1) )+ " . $updatedtree->lft . ",
                    rgt = (rgt *(-1) ) + " . $updatedtree->lft . ",
                    hlevel = hlevel +" . $updatedtree->hlevel . "
                    WHERE
                    lft < 0 AND rgt < 0 
                    AND root_id = " . $this->root_id . "
                    AND table_name = " . $this->db->escape($this->table_name) . ";";

            //echo "<br>".$sql;
            $this->db->query($sql);

            return true;
        } else {
            return false;
        }
    }

    // Move to the left of a node

    public function moveToLeftOf($id) {
        if ($this->getNodeById()) {

            if ($this->asset_id == $id) {
                return false;
            }

            $right_node = new MY_Model($this->table_name);
            $right_node->asset_id = $id;
            $right_node->getNodeById();

            $nodesize = $this->rgt - $this->lft + 1;


            //negate current node and children
            $sql = "UPDATE " . $this->db->escape_str($this->table) . "
                    SET lft = ((lft - " . $this->lft . ") + 1) * (-1), 
                    rgt = ((rgt - " . $this->lft . ") + 1) * (-1),
                    hlevel = (hlevel -" . $this->hlevel . ")
                    WHERE 
                    lft >= " . $this->lft . " 
                    AND rgt <= " . $this->rgt . "
                    AND root_id = " . $this->root_id . "
                    AND table_name = " . $this->db->escape($this->table_name) . ";";
            //echo "<br>".$sql;
            $this->db->query($sql);


            //update tree 
            $sql = "UPDATE " . $this->db->escape_str($this->table) . " SET
                    lft = lft - " . $nodesize . "
                    WHERE
                    lft > " . $this->rgt . "  
                    AND root_id = " . $this->root_id . "
                    AND table_name = " . $this->db->escape($this->table_name) . ";";

            //echo "<br>".$sql;
            $this->db->query($sql);

            //update tree 
            $sql = "UPDATE " . $this->db->escape_str($this->table) . " SET
                    rgt = rgt - " . $nodesize . "
                    WHERE
                    rgt > " . $this->rgt . "  
                    AND root_id = " . $this->root_id . "
                    AND table_name = " . $this->db->escape($this->table_name) . ";";

            //echo "<br>".$sql;
            $this->db->query($sql);


            // Update parent id
            $sql = "UPDATE " . $this->db->escape_str($this->table) . " SET
                    parent_id = " . $right_node->parent_id . "
                    WHERE 
                    asset_id = " . $this->db->escape($this->asset_id) . " 
                    AND root_id = " . $this->root_id . "
                    AND table_name = " . $this->db->escape($this->table_name) . ";";


            //echo "<br>".$sql;
            $this->db->query($sql);


            // Update tree 
            $updatedright = new MY_Model($this->table_name);
            $updatedright->asset_id = $id;
            $updatedright->getNodeById();

            //update tree for insertion
            $sql = "UPDATE " . $this->db->escape_str($this->table) . " SET
                    lft = lft + " . $nodesize . " 
                    WHERE
                    lft >= " . $updatedright->lft . " 
                    AND root_id = " . $this->root_id . "
                    AND table_name = " . $this->db->escape($this->table_name) . ";";

            //echo "<br>".$sql;
            $this->db->query($sql);


            $sql = "UPDATE " . $this->db->escape_str($this->table) . " SET
                    rgt = rgt + " . $nodesize . " 
                    WHERE
                    rgt > " . $updatedright->lft . " 
                    AND root_id = " . $this->root_id . "
                    AND table_name = " . $this->db->escape($this->table_name) . ";";

            //echo "<br>".$sql;
            $this->db->query($sql);

            $updatedright->getNodeById();
            //update pluged nodes
            $sql = "UPDATE " . $this->db->escape_str($this->table) . " SET
                    lft = (lft *(-1) )+ " . ($updatedright->lft - $nodesize - 1) . ",
                    rgt = (rgt *(-1) ) + " . ($updatedright->lft - $nodesize - 1) . ",
                    hlevel = hlevel +" . $updatedright->hlevel . "
                    WHERE
                    lft < 0 AND rgt < 0 
                    AND root_id = " . $this->root_id . "
                    AND table_name = " . $this->db->escape($this->table_name) . ";";

            //echo "<br>".$sql;
            $this->db->query($sql);

            return true;
        } else {

            return false;
        }
    }

    // Move to the right of a node

    public function moveToRightOf($id) {
        if ($this->getNodeById()) {

            if ($this->asset_id == $id) {
                return false;
            }

            $left_node = new MY_Model($this->table_name);
            $left_node->asset_id = $id;
            $left_node->getNodeById();

            $nodesize = $this->rgt - $this->lft + 1;


            //negate current node and children
            $sql = "UPDATE " . $this->db->escape_str($this->table) . "
                    SET lft = ((lft - " . $this->lft . ") + 1) * (-1), 
                    rgt = ((rgt - " . $this->lft . ") + 1) * (-1),
                    hlevel = (hlevel -" . $this->hlevel . ")
                    WHERE 
                    lft >= " . $this->lft . " 
                    AND rgt <= " . $this->rgt . "
                    AND root_id = " . $this->root_id . "
                    AND table_name = " . $this->db->escape($this->table_name) . ";";
            //echo "<br>".$sql;
            $this->db->query($sql);


            //update tree 
            $sql = "UPDATE " . $this->db->escape_str($this->table) . " SET
                    lft = lft - " . $nodesize . "
                    WHERE
                    lft > " . $this->rgt . "  
                    AND root_id = " . $this->root_id . "
                    AND table_name = " . $this->db->escape($this->table_name) . ";";

            //echo "<br>".$sql;
            $this->db->query($sql);

            //update tree 
            $sql = "UPDATE " . $this->db->escape_str($this->table) . " SET
                    rgt = rgt - " . $nodesize . "
                    WHERE
                    rgt > " . $this->rgt . "  
                    AND root_id = " . $this->root_id . "
                    AND table_name = " . $this->db->escape($this->table_name) . ";";

            //echo "<br>".$sql;
            $this->db->query($sql);


            // Update parent id
            $sql = "UPDATE " . $this->db->escape_str($this->table) . " SET
                    parent_id = " . $left_node->parent_id . "
                    WHERE 
                    asset_id = " . $this->db->escape($this->asset_id) . " 
                    AND root_id = " . $this->root_id . "
                    AND table_name = " . $this->db->escape($this->table_name) . ";";


            //echo "<br>".$sql;
            $this->db->query($sql);


            // Updated tree 
            $updatedleft = new MY_Model($this->table_name);
            $updatedleft->asset_id = $id;
            $updatedleft->getNodeById();

            //update tree for insertion
            $sql = "UPDATE " . $this->db->escape_str($this->table) . " SET
                    lft = lft + " . $nodesize . " 
                    WHERE
                    lft > " . $updatedleft->rgt . " 
                    AND root_id = " . $this->root_id . "
                    AND table_name = " . $this->db->escape($this->table_name) . ";";

            //echo "<br>".$sql;
            $this->db->query($sql);


            $sql = "UPDATE " . $this->db->escape_str($this->table) . " SET
                    rgt = rgt + " . $nodesize . " 
                    WHERE
                    rgt > " . $updatedleft->rgt . " 
                    AND root_id = " . $this->root_id . "
                    AND table_name = " . $this->db->escape($this->table_name) . ";";

            //echo "<br>".$sql;
            $this->db->query($sql);

            $updatedleft->getNodeById();
            //update pluged nodes
            $sql = "UPDATE " . $this->db->escape_str($this->table) . " SET
                    lft = (lft *(-1) )+ " . $updatedleft->rgt . ",
                    rgt = (rgt *(-1) ) + " . $updatedleft->rgt . ",
                    hlevel = hlevel +" . $updatedleft->hlevel . "
                    WHERE
                    lft < 0 AND rgt < 0 
                    AND root_id = " . $this->root_id . "
                    AND table_name = " . $this->db->escape($this->table_name) . ";";

            //echo "<br>".$sql;
            $this->db->query($sql);

            return true;
        } else {

            return false;
        }
    }

    //Delete a node and all its child nodes

    public function deleteNode() {
        if ($this->getNodeById()) {

            $nodesize = $this->rgt - $this->lft + 1;
            $sql = "DELETE FROM " . $this->db->escape_str($this->table) . " 
                    WHERE lft >=  " . $this->lft . " AND rgt <= " . $this->rgt . " 	 
                    AND root_id = " . $this->root_id . "
                    AND table_name = " . $this->db->escape($this->table_name) . ";";

            $this->db->query($sql);

            $sql = "UPDATE " . $this->db->escape_str($this->table) . " SET lft = lft - " . $nodesize . "
                    WHERE lft > " . $this->rgt . " 
                    AND root_id = " . $this->root_id . "
                    AND table_name = " . $this->db->escape($this->table_name) . ";";

            $this->db->query($sql);

            $sql = "UPDATE " . $this->db->escape_str($this->table) . " SET rgt = rgt- " . $nodesize . "
                    WHERE rgt > " . $this->rgt . " 
                    AND root_id = " . $this->root_id . "
                    AND table_name = " . $this->db->escape($this->table_name) . ";";

            $this->db->query($sql);

            return true;
        } else {
            return false;
        }
    }

    //check whether has children

    public function hasChild() {

        $this->getNodeById();

        $sql = "SELECT asset_id FROM " . $this->db->escape_str($this->table) . " 
		WHERE parent_id = " . $this->db->escape($this->asset_id) . "
		AND root_id = " . $this->root_id . "
		AND table_name = " . $this->db->escape($this->table_name) . ";";
        //echo $sql;
        $result = $this->db->query($sql);
        if ($result->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

    function toNestedArray($collection) {
        // Trees mapped
        $trees = array();
        $l = 0;
        if (count($collection) > 0) {
            // Node Stack. Used to help building the hierarchy
            $stack = array();

            foreach ($collection as $node) {
                $item = $node;
                $item['children'] = array();

                // Number of stack items
                $l = count($stack);

                // Check if we're dealing with different levels
                while ($l > 0 && $stack[$l - 1]['hlevel'] >= $item['hlevel']) {
                    array_pop($stack);
                    $l--;
                }

                // Stack is empty (we are inspecting the root)
                if ($l == 0) {
                    // Assigning the root node
                    $i = count($trees);

                    $trees[$i] = $item;
                    $stack[] = & $trees[$i];
                } else {

                    // Add node to parent
                    $i = count($stack[$l - 1]['children']);


                    $stack[$l - 1]['children'][$i] = $item;
                    $stack[] = & $stack[$l - 1]['children'][$i];
                }
            }
        }

        return $trees;
    }

/////////////////////////////////////////////

    public function getChildrenByGroupConcat() {
        $this->getNodeById();

        $sql = "SELECT group_concat(asset_id) ids FROM " . $this->db->escape_str($this->table) . " 
		WHERE 
                lft > " . $this->lft . " AND rgt < " . $this->rgt . "  
		AND root_id = " . $this->root_id . "
		AND table_name = " . $this->db->escape($this->table_name) . ";";
        //echo $sql;
        $result = $this->db->query($sql);
        if ($g = $result->row()) {
            return $g->ids;
        } else {
            return false;
        }
    }

    function nest(&$cats) {
        $new = array();

        while (list($id, $cat) = each($cats)) {
            $new[$cat['id']] = $cat;

            if ($cat['rgt'] - $cat['lft'] != 1) {
                $new[$cat['id']]['children'] = $this->nest($cats, true);
            }

            $next_id = key($cats);

            if ($next_id && $cats[$next_id]['parent_id'] != $cat['parent_id']) {
                return $new;
            }
        }

        return $new;
    }

    ////////////////////datatble functions///////////////////////////

    private function _get_datatables_query() {

        $this->db->from($this->select_table);
        if ($this->select) {
            $this->db->select($this->select);
        }
        if ($this->join) {
            foreach ($this->join as $jtable) {
                $this->db->join($jtable['t'], $jtable['j'], $jtable['jt']);
            }
        }

        if ($this->where) {
            foreach ($this->where as $w) {
                $this->db->where($w);
            }
        }

        if ($this->group_by) {
            foreach ($this->group_by as $g) {
                $this->db->group_by($g);
            }
        }

        $i = 0;

        foreach ($this->column_search as $item) { // loop column 
            if ($this->post_search['value']) { // if datatable send POST for search
                if ($i === 0) { // first loop
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $this->post_search['value']);
                } else {
                    $this->db->or_like($item, $this->post_search['value']);
                }

                if (count($this->column_search) - 1 == $i) { //last loop
                    $this->db->group_end(); //close bracket
                }
            }
            $i++;
        }
        if (isset($this->like)) {
            foreach ($this->like as $item) { // loop column 
                if ($item) { // if datatable send POST for search
                    $this->db->like($item['q'], $item['s']);
                }
            }
        }

        if (isset($this->group_where_in)) {
            $j = 0;

            foreach ($this->group_where_in as $item) { // loop column 
                if ($item) { // if datatable send POST for search
                    if ($i === 0) { // first loop
                        $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                        $this->db->where_in($item['q'], $item['s']);
                    } else {
                        $this->db->where_in($item['q'], $item['s']);
                    }
                    if (count($this->group_where_in) - 1 == $i) { //last loop
                        $this->db->group_end(); //close bracket
                    }
                }
                $j++;
            }
        }


        if (isset($this->post_order)) { // here order processing    
            $this->db->order_by($this->column_order[$this->post_order['0']['column']], $this->post_order['0']['dir']);
        } else if (isset($this->order)) {
            //$order = $this->order;
            foreach ($this->order as $key => $value) {
                $this->db->order_by($key, $value);
            }
        }
    }

    private function _count_filtered() {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    private function _count_all() {
        $this->db->from($this->select_table);
        return $this->db->count_all_results();
    }

    public function get_datatables() {
        $this->_get_datatables_query();
        if ($this->post_length != -1)
            $this->db->limit($this->post_length, $this->post_start);
        $rs = $this->db->get();
        //echo $this->db->last_query();
        $items = array();
        $n = 1;
        foreach ($rs->result_array() as $row) {
            $row['sl'] = $this->post_start + $n;
            array_push($items, $row);
            $n++;
        }
        $output = array(
            "draw" => $this->post_draw,
            "recordsTotal" => $this->_count_filtered(),
            "recordsFiltered" => $this->_count_filtered(),
            "data" => $items,
        );
       // echo $this->db->last_query();
        return $output;
    }

}
