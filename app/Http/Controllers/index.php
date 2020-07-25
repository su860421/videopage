<?php
    class note
    {
        private $n;
        private $m;
        private $x_array=array();
        private $y_array=array();
        private $f_array=array();
        private $s_array=array();
        public function __construct($n_in, $m_in)
        {
            $this->n=$n_in;
            $this->m=$m_in;
            for ($i=0;$i<$n_in*$m_in;$i++) {
                $this->x_array[$i]=0;
                $this->y_array[$i]=0;
                $this->f_array[$i]=0;
                $this->s_array[$i]=0;
            }
        }
        public function x($num_x, $array_num)
        {
            $this->x_array[$array_num]=$num_x;
            return $this->x_array;
        }
        public function f($num_f, $array_num)
        {
            $this->f_array[$array_num]=$num_f;
            return $this->f_array;
        }
        public function y($num_y, $array_num)
        {
            $this->y_array[$array_num]=$num_y;
            return $this->y_array;
        }
        public function s($num_s, $array_num)
        {
            $this->s_array[$array_num]=$num_s;
            return $this->s_array;
        }
    }

    if (isset($_POST["send"])) {
        $data_user=$_POST["user"];
        $maze_data = preg_split("/[\s,]+/", $data_user);
        $num=0;
        foreach ($maze_data as $value) {
            $maze[$num]=preg_split('//', $value, -1, PREG_SPLIT_NO_EMPTY);
            $num+=1;
        }
        $num=0;
        $num1=0;

        /*$endx=array();
        $endy=array();*/
        $cha=0;
        $mouse=array();
        foreach ($maze as $value) {
            foreach ($value as $val) {
                if ($val=="C") {
                    $starty=$num1;//未設定
                    $startx=$num;
                }
                if ($val=="X") {
                    $endy[$cha]=$num1;
                    $endx[$cha]=$num;
                    $mouse[$cha]="X";
                    $cha+=1;
                }
                if ($val=="Y") {
                    $endy[$cha]=$num1;
                    $endx[$cha]=$num;
                    $mouse[$cha]="Y";
                    $cha+=1;
                }
                if ($val=="Z") {
                    $endy[$cha]=$num1;
                    $endx[$cha]=$num;
                    $mouse[$cha]="Z";
                    $cha+=1;
                }
                $num1+=1;
            }
            $num1=0;
            $num+=1;
        }


        $n=count($maze);
        $m=count($maze[0]);

        //---------------------------------------------------------------------
        $step=array();
        for ($nu=0;$nu<3;$nu++) {
            $book=array();
            for ($i=0;$i<$n;$i++) {
                for ($j=0;$j<$m;$j++) {
                    $book[$i][$j]=0;
                }
            }
            $next=array([0,1],[1,0],[0,-1],[-1,0]);
            $head=0;
            $tail=0;

            $que=new note($n, $m);
            //echo json_encode($x);
            $x=$que->x($startx, $tail);
            $y=$que->y($starty, $tail);
            $f=$que->f(0, $tail);
            $s=$que->s(0, $tail);
            $tail+=1;

            $book[$startx][$starty]=1;

            $flag=0;

            while ($head<$tail) {
                for ($i=0;$i<4;$i++) {
                    $tx=$x[$head]+$next[$i][0];
                    $ty=$y[$head]+$next[$i][1];
                    if ($tx<0||$tx>$n-1||$ty<0||$ty>$m-1) {
                        continue;
                    }

                    if (($maze[$tx][$ty]==$mouse[$nu]||$maze[$tx][$ty]=="0") && $book[$tx][$ty]==0) {
                        $book[$tx][$ty]=1;
                        echo json_encode($maze[$tx][$ty]);
                        echo json_encode('    ');
                        // echo json_encode((array)$mouse);
                        // echo json_encode('    ');
                        // echo json_encode($maze[$tx][$ty]);
                        // echo json_encode('    ');
                        // echo json_encode($s[$head]);

                        $x=$que->x($tx, $tail);
                        $y=$que->y($ty, $tail);
                        $f=$que->f($head, $tail);
                        $s=$que->s($s[$head]+1, $tail);
                        $tail+=1;
                    }
                    if ($tx==$endx[$nu]&&$ty==$endy[$nu]) {
                        $flag=1;
                        break;
                    }
                }
                if ($flag==1) {
                    break;
                }
                $head+=1;
            }
            $step[$nu]=$s[$tail-1]-1;
            echo '<br>';
            echo json_encode((array)$s);
            echo '<br>';
            echo json_encode((array)$x);
            echo '<br>';
            echo json_encode((array)$y);
            echo '<br>';
        }

        echo json_encode((array)$step);
    //echo($s[$tail-1]);
    } else {
        print("請重新輸入.....");
    }
?>
