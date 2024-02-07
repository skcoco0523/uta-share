<div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
    <div class="menu_section">
    <h3>メニュー</h3>
    <ul class="nav side-menu">
        <li><a href="{{ url('dashboard') }}"><i class="fa fa-home"></i> Home <span class="fa fa-pagelines"></span></a>
        </li>
        <li><a><i class="fa fa-question-circle"></i>1. 問い合わせ <span class="fa fa-chevron-down"></span></a>
        <ul class="nav child_menu">
            <li><a href="{{ url('Toiawase.Sinki_Touroku') }}"> 1.1 新規登録 </a></li>
            <li><a href="{{ url('Toiawase.Henkou_Touroku') }}">1.2 変更登録</a></li>
            <li><a href="{{ url('Toiawase.Ichiran') }}">1.3 問い合わせ一覧</a></li>
   
        </ul>
        </li>
        <li><a><i class="fa fa-desktop"></i> 2. 設定変更 <span class="fa fa-chevron-down"></span></a>
        <ul class="nav child_menu">
            <li><a href="{{ url('Settei_Henkou.Sinki_Touroku') }}"> 2.1 新規登録 </a></li>
            <li><a href="{{ url('Settei_Henkou.Henkou_Touroku') }}"> 2.2 変更登録 </a></li>
            <li><a href="{{ url('Settei_Henkou.Ichiran') }}"> 2.3 設定変更一覧 </a></li>
            <li><a href="{{ url('Settei_Henkou.Sisutemu_Henkou_Nitteikanri') }}"> 2.4 システム変更日程管理 </a></li>
           
        </ul>
        </li>
        <li><a><i class="fa fa-cubes"></i> 3. エジェント <span class="fa fa-chevron-down"></span></a>
        <ul class="nav child_menu">
            <li><a href="{{ url('Eziento.Read') }}"> 3.1 データ読み込み </a></li>
            <li><a href="{{ url('Eziento.Read_Data_Manual') }}"> 3.2 エジェント手動入力</a></li>
            <li><a href="{{ url('Eziento.ID.Touroku') }}"> 3.3 エジェントID登録</a></li>
            <li><a href="{{ url('Eziento.Syouzoku.Ichiran') }}"> 3.4 エジェント組織管理</a></li>
        </ul>
        <li><a><i class="fa fa-bar-chart-o"></i> 4. 集計 <span class="fa fa-chevron-down"></span></a>

        <ul class="nav child_menu">
            <li><a>4.1 問い合わせ集計<span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu">
                <li><a href="{{ url('Toiawase.Syuukei') }}">4.1.1 期間集計</a></li>
                <li><a href="{{ url('Toiawase.Uchiwake.Gurapu') }}">4.1.2 内訳表・グラフ</a></li>
            </ul>
            <li><a>4.2 設定変更集計<span class="fa fa-chevron-down"></span></a>
            <ul class="nav child_menu">
                <li><a href="{{ url('Settei_Henkou.Syuukei') }}">4.2.1 期間集計</a></li>
                <li><a href="{{ url('Settei_Henkou.Uchiwake') }}">4.2.2 内訳表・グラフ</a></li>
            </ul>
            
            <li><a href="{{ url('Eziento.Syuukei') }}"> 4.3 エジェント集計・内訳</a></li>
            <li><a href="{{ url('../IM') }}"> 4.4 インシデント集計・内訳</a></li>
        </ul>
        </li>
        <li><a><i class="fa fa-user"></i> 5. 依頼者 <span class="fa fa-chevron-down"></span></a>
        <ul class="nav child_menu">
            <li><a href="{{ url('Iraisya.Iraisya_Touroku') }}"> 5.1 依頼者登録</a></li>
            <li><a href="{{ url('Iraisya.Iraisya_Henkou') }}"> 5.2 依頼者変更</a></li>
            <li><a href="{{ url('Iraisya.Syozoku_Touroku_Init') }}"> 5.3 所属登録</a></li>
            <li><a href="{{ url('Iraisya.Ichiran')}}"> 5.4 依頼者一覧</a></li>
        </ul>
        </li>
        <li><a><i class="fa fa-cube"></i> 6. 課題管理 <span class="fa fa-chevron-down"></span></a>
        <ul class="nav child_menu">
            <li><a href="{{ url('ProjectManagement') }}"> 6.1 課題起票</a></li>
            <li><a href="{{ url('ProjectManagement/Show') }}"> 6.2 課題管理一覧</a></li>
        </ul>
        </li>
    </ul>
    </div>
    <div class="menu_section">
    <h3>最近良く使いう</h3>
    <ul class="nav side-menu">
        <li><a><i class="fa fa-file-archive-o"></i> 7. 報告資料関連 <span class="fa fa-chevron-down"></span></a>
        <ul class="nav child_menu">
            <li><a href="{{ url('Toiawase.Report') }}"> 7.1 問い合わせ レポート</a></li>
            <li><a href='#'> 7.2 設定変更 レポート </a></li>
            <li><a href="#"> 7.3 エジェント レポート </a></li>
            <li><a href="{{ url('Weekly_Report') }}"> 7.4 週次報告書 </a></li>
        </ul>
        </li>
        <li><a href="{{ url('fullcalendar.index') }}"><i class="fa fa-calendar"></i> 8. スケジューラ <span class="fa fa-chevron-down"></span></a>
      
        @if(Auth::Check())
        
         @if(Auth::user()->permission == 0)
         <li><a href="{{ url('ForcePWD.Show') }}"><i class="fa fa-calendar"></i> 9. パスワード初期設定 <span class="fa fa-chevron-down"></span></a>
         @endif
        @endif
        
       
        <li><a href="{{ url('Fairu.Kanri') }}"><i class="fa fa-laptop"></i> ファイル管理 <span class="label label-success pull-right">開発中</span></a></li>
        </ul>
    </div>

</div>