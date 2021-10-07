<!DOCTYPE html>
<html>
    <head>
        <title> Generate pdf </title>
        {{--<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">--}}
    </head>
    <body style="background-color: #ABABAB !important;">
        <div class="pdf-wrapper">
            <div class="pdf-head">
                <a href="{{ route('agent.report.attendance-report-pdf-download') }}"><button>Download</button></a>
            </div>
            <div class="pdf_table">
                <div class="pdf-table-top" style="margin-bottom:30px;">
                    <div class="logo-part" style="width:9%;display: inline-block;">
                        <img src="http://demo.pihr.xyz/PihrDocument/CompanyImages/1/1.png" width="80px">
                    </div>
                    <div class="address-part" style="text-align: center;width:90%;display: inline-block;">
                        <h2>VIVACOM SOLUTIONS</h2>
                        <span style="font-size: 14px;">457, Floor # 4, House 6, Road No 3, Dhaka 1216</span><br>
                        <span><b>Attendance Report For Year:2019 , Month: March</b></span>
                    </div>

                </div>
                <div><hr></div>
                <div style="font-size:10px;text-align: right;">
                    Print Date: 13-Mar-2019 03:11:34 PM
                </div>
              {{-- <table width="100%" class="table" style="page-break-inside: avoid;">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>In/Out</th>
                            <th>01</th>
                            <th>02</th>
                            <th>03</th>
                            <th>04</th>
                            <th>05</th>
                            <th>06</th>
                            <th>07</th>
                            <th>08</th>
                            <th>09</th>
                            <th>10</th>
                            <th>11</th>
                            <th>12</th>
                            <th>13</th>
                            <th>14</th>
                            <th>15</th>
                            <th>16</th>
                            <th>17</th>
                            <th>18</th>
                            <th>19</th>
                            <th>20</th>
                            <th>21</th>
                            <th>22</th>
                            <th>23</th>
                            <th>24</th>
                            <th>25</th>
                            <th>26</th>
                            <th>27</th>
                            <th>28</th>
                            <th>29</th>
                            <th>30</th>
                            <th>31</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td rowspan="3">
                                016<br>
                                Md. Shahin Hossain<br>
                                Executive<br>
                            </td>
                            <td>In</td>
                            <td>10</td>
                            <td>11</td>
                            <td>10</td>
                            <td>11</td>
                            <td>10</td>
                            <td>11</td>
                            <td>11</td>
                            <td>10</td>
                            <td>10</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>In</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>11</td>
                            <td>11</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                        </tr>
                        <tr>
                            <td>Out</td>
                            <td>10</td>
                            <td>11</td>
                            <td>10</td>
                            <td>11</td>
                            <td>10</td>
                            <td>11</td>
                            <td>11</td>
                            <td>10</td>
                            <td>10</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>In</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>11</td>
                            <td>11</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                        </tr>
                        <tr>
                            <td colspan="32" style="text-align:right;">
                                <span class="bold" style="margin-right:30px;">Total Present : 6</span>
                                <span class="bold" style="margin-right:30px;">Total Absent : 20</span>
                                <span class="bold">Avg. Working Hours : -4.42 (H)</span>
                            </td>
                        </tr>

                        <tr>
                            <td rowspan="3">
                                016<br>
                                Md. Shahin Hossain<br>
                                Executive<br>
                            </td>
                            <td>In</td>
                            <td>10</td>
                            <td>11</td>
                            <td>10</td>
                            <td>11</td>
                            <td>10</td>
                            <td>11</td>
                            <td>11</td>
                            <td>10</td>
                            <td>10</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>In</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>11</td>
                            <td>11</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                        </tr>
                        <tr>
                            <td>Out</td>
                            <td>10</td>
                            <td>11</td>
                            <td>10</td>
                            <td>11</td>
                            <td>10</td>
                            <td>11</td>
                            <td>11</td>
                            <td>10</td>
                            <td>10</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>In</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>11</td>
                            <td>11</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                        </tr>
                        <tr>
                            <td colspan="32" style="text-align:right;">
                                <span class="bold" style="margin-right:30px;">Total Present : 6</span>
                                <span class="bold" style="margin-right:30px;">Total Absent : 20</span>
                                <span class="bold">Avg. Working Hours : -4.42 (H)</span>
                            </td>
                        </tr>

                        <tr>
                            <td rowspan="3">
                                016<br>
                                Md. Shahin Hossain<br>
                                Executive<br>
                            </td>
                            <td>In</td>
                            <td>10</td>
                            <td>11</td>
                            <td>10</td>
                            <td>11</td>
                            <td>10</td>
                            <td>11</td>
                            <td>11</td>
                            <td>10</td>
                            <td>10</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>In</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>11</td>
                            <td>11</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                        </tr>
                        <tr>
                            <td>Out</td>
                            <td>10</td>
                            <td>11</td>
                            <td>10</td>
                            <td>11</td>
                            <td>10</td>
                            <td>11</td>
                            <td>11</td>
                            <td>10</td>
                            <td>10</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>In</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>11</td>
                            <td>11</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                        </tr>
                        <tr>
                            <td colspan="32" style="text-align:right;">
                                <span class="bold" style="margin-right:30px;">Total Present : 6</span>
                                <span class="bold" style="margin-right:30px;">Total Absent : 20</span>
                                <span class="bold">Avg. Working Hours : -4.42 (H)</span>
                            </td>
                        </tr>

                        <tr>
                            <td rowspan="3">
                                016<br>
                                Md. Shahin Hossain<br>
                                Executive<br>
                            </td>
                            <td>In</td>
                            <td>10</td>
                            <td>11</td>
                            <td>10</td>
                            <td>11</td>
                            <td>10</td>
                            <td>11</td>
                            <td>11</td>
                            <td>10</td>
                            <td>10</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>In</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>11</td>
                            <td>11</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                        </tr>
                        <tr>
                            <td>Out</td>
                            <td>10</td>
                            <td>11</td>
                            <td>10</td>
                            <td>11</td>
                            <td>10</td>
                            <td>11</td>
                            <td>11</td>
                            <td>10</td>
                            <td>10</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>In</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>11</td>
                            <td>11</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                        </tr>
                        <tr>
                            <td colspan="32" style="text-align:right;">
                                <span class="bold" style="margin-right:30px;">Total Present : 6</span>
                                <span class="bold" style="margin-right:30px;">Total Absent : 20</span>
                                <span class="bold">Avg. Working Hours : -4.42 (H)</span>
                            </td>
                        </tr>

                        <tr>
                            <td rowspan="3">
                                016<br>
                                Md. Shahin Hossain<br>
                                Executive<br>
                            </td>
                            <td>In</td>
                            <td>10</td>
                            <td>11</td>
                            <td>10</td>
                            <td>11</td>
                            <td>10</td>
                            <td>11</td>
                            <td>11</td>
                            <td>10</td>
                            <td>10</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>In</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>11</td>
                            <td>11</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                        </tr>
                        <tr>
                            <td>Out</td>
                            <td>10</td>
                            <td>11</td>
                            <td>10</td>
                            <td>11</td>
                            <td>10</td>
                            <td>11</td>
                            <td>11</td>
                            <td>10</td>
                            <td>10</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>In</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>11</td>
                            <td>11</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                        </tr>
                        <tr>
                            <td colspan="32" style="text-align:right;">
                                <span class="bold" style="margin-right:30px;">Total Present : 6</span>
                                <span class="bold" style="margin-right:30px;">Total Absent : 20</span>
                                <span class="bold">Avg. Working Hours : -4.42 (H)</span>
                            </td>
                        </tr>

                        <tr>
                            <td rowspan="3">
                                016<br>
                                Md. Shahin Hossain<br>
                                Executive<br>
                            </td>
                            <td>In</td>
                            <td>10</td>
                            <td>11</td>
                            <td>10</td>
                            <td>11</td>
                            <td>10</td>
                            <td>11</td>
                            <td>11</td>
                            <td>10</td>
                            <td>10</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>In</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>11</td>
                            <td>11</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                        </tr>
                        <tr>
                            <td>Out</td>
                            <td>10</td>
                            <td>11</td>
                            <td>10</td>
                            <td>11</td>
                            <td>10</td>
                            <td>11</td>
                            <td>11</td>
                            <td>10</td>
                            <td>10</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>In</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>11</td>
                            <td>11</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                        </tr>
                        <tr>
                            <td colspan="32" style="text-align:right;">
                                <span class="bold" style="margin-right:30px;">Total Present : 6</span>
                                <span class="bold" style="margin-right:30px;">Total Absent : 20</span>
                                <span class="bold">Avg. Working Hours : -4.42 (H)</span>
                            </td>
                        </tr>

                        <tr>
                            <td rowspan="3">
                                016<br>
                                Md. Shahin Hossain<br>
                                Executive<br>
                            </td>
                            <td>In</td>
                            <td>10</td>
                            <td>11</td>
                            <td>10</td>
                            <td>11</td>
                            <td>10</td>
                            <td>11</td>
                            <td>11</td>
                            <td>10</td>
                            <td>10</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>In</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>11</td>
                            <td>11</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                        </tr>
                        <tr>
                            <td>Out</td>
                            <td>10</td>
                            <td>11</td>
                            <td>10</td>
                            <td>11</td>
                            <td>10</td>
                            <td>11</td>
                            <td>11</td>
                            <td>10</td>
                            <td>10</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>In</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>11</td>
                            <td>11</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                        </tr>
                        <tr>
                            <td colspan="32" style="text-align:right;">
                                <span class="bold" style="margin-right:30px;">Total Present : 6</span>
                                <span class="bold" style="margin-right:30px;">Total Absent : 20</span>
                                <span class="bold">Avg. Working Hours : -4.42 (H)</span>
                            </td>
                        </tr>
                    </tbody>
                </table>--}}
                <table width="100%" class="table">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>In/Out</th>
                            <th>01</th>
                            <th>02</th>
                            <th>03</th>
                            <th>04</th>
                            <th>05</th>
                            <th>06</th>
                            <th>07</th>
                            <th>08</th>
                            <th>09</th>
                            <th>10</th>
                            <th>11</th>
                            <th>12</th>
                            <th>13</th>
                            <th>14</th>
                            <th>15</th>
                            <th>16</th>
                            <th>17</th>
                            <th>18</th>
                            <th>19</th>
                            <th>20</th>
                            <th>21</th>
                            <th>22</th>
                            <th>23</th>
                            <th>24</th>
                            <th>25</th>
                            <th>26</th>
                            <th>27</th>
                            <th>28</th>
                            <th>29</th>
                            <th>30</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="margin-bottom: 15px;">
                            <td rowspan="3">Td 1</td>
                            <td>Td 2</td>
                            <td>Td 3</td>
                            <td>Td 4</td>
                            <td>Td 5</td>
                        </tr>
                        <tr>
                            <td>Td 2</td>
                            <td>Td 3</td>
                            <td>Td 4</td>
                            <td>Td 5</td>
                        </tr>
                        <tr>
                            <td colspan="5" style="text-align:right;">
                                <span class="bold" style="margin-right:30px;">Total Present : 6</span>
                                <span class="bold" style="margin-right:30px;">Total Absent : 20</span>
                                <span class="bold">Avg. Working Hours : -4.42 (H)</span>
                            </td>
                        </tr>

                        <tr style="margin-bottom: 15px;">
                            <td rowspan="3">Td 1</td>
                            <td>Td 2</td>
                            <td>Td 3</td>
                            <td>Td 4</td>
                            <td>Td 5</td>
                        </tr>
                        <tr>
                            <td>Td 2</td>
                            <td>Td 3</td>
                            <td>Td 4</td>
                            <td>Td 5</td>
                        </tr>
                        <tr>
                            <td colspan="5" style="text-align:right;">
                                <span class="bold" style="margin-right:30px;">Total Present : 6</span>
                                <span class="bold" style="margin-right:30px;">Total Absent : 20</span>
                                <span class="bold">Avg. Working Hours : -4.42 (H)</span>
                            </td>
                        </tr>
                        <tr style="margin-bottom: 15px;">
                            <td rowspan="3">Td 1</td>
                            <td>Td 2</td>
                            <td>Td 3</td>
                            <td>Td 4</td>
                            <td>Td 5</td>
                        </tr>
                        <tr>
                            <td>Td 2</td>
                            <td>Td 3</td>
                            <td>Td 4</td>
                            <td>Td 5</td>
                        </tr>
                        <tr>
                            <td colspan="5" style="text-align:right;">
                                <span class="bold" style="margin-right:30px;">Total Present : 6</span>
                                <span class="bold" style="margin-right:30px;">Total Absent : 20</span>
                                <span class="bold">Avg. Working Hours : -4.42 (H)</span>
                            </td>
                        </tr>
                        <tr style="margin-bottom: 15px;">
                            <td rowspan="3">Td 1</td>
                            <td>Td 2</td>
                            <td>Td 3</td>
                            <td>Td 4</td>
                            <td>Td 5</td>
                        </tr>
                        <tr>
                            <td>Td 2</td>
                            <td>Td 3</td>
                            <td>Td 4</td>
                            <td>Td 5</td>
                        </tr>
                        <tr>
                            <td colspan="5" style="text-align:right;">
                                <span class="bold" style="margin-right:30px;">Total Present : 6</span>
                                <span class="bold" style="margin-right:30px;">Total Absent : 20</span>
                                <span class="bold">Avg. Working Hours : -4.42 (H)</span>
                            </td>
                        </tr>
                        <tr style="margin-bottom: 15px;">
                            <td rowspan="3">Td 1</td>
                            <td>Td 2</td>
                            <td>Td 3</td>
                            <td>Td 4</td>
                            <td>Td 5</td>
                        </tr>
                        <tr>
                            <td>Td 2</td>
                            <td>Td 3</td>
                            <td>Td 4</td>
                            <td>Td 5</td>
                        </tr>
                        <tr>
                            <td colspan="5" style="text-align:right;">
                                <span class="bold" style="margin-right:30px;">Total Present : 6</span>
                                <span class="bold" style="margin-right:30px;">Total Absent : 20</span>
                                <span class="bold">Avg. Working Hours : -4.42 (H)</span>
                            </td>
                        </tr>
                        <tr style="margin-bottom: 15px;">
                            <td rowspan="3">Td 1</td>
                            <td>Td 2</td>
                            <td>Td 3</td>
                            <td>Td 4</td>
                            <td>Td 5</td>
                        </tr>
                        <tr>
                            <td>Td 2</td>
                            <td>Td 3</td>
                            <td>Td 4</td>
                            <td>Td 5</td>
                        </tr>
                        <tr>
                            <td colspan="5" style="text-align:right;">
                                <span class="bold" style="margin-right:30px;">Total Present : 6</span>
                                <span class="bold" style="margin-right:30px;">Total Absent : 20</span>
                                <span class="bold">Avg. Working Hours : -4.42 (H)</span>
                            </td>
                        </tr><tr style="margin-bottom: 15px;">
                            <td rowspan="3">Td 1</td>
                            <td>Td 2</td>
                            <td>Td 3</td>
                            <td>Td 4</td>
                            <td>Td 5</td>
                        </tr>
                        <tr>
                            <td>Td 2</td>
                            <td>Td 3</td>
                            <td>Td 4</td>
                            <td>Td 5</td>
                        </tr>
                        <tr>
                            <td colspan="5" style="text-align:right;">
                                <span class="bold" style="margin-right:30px;">Total Present : 6</span>
                                <span class="bold" style="margin-right:30px;">Total Absent : 20</span>
                                <span class="bold">Avg. Working Hours : -4.42 (H)</span>
                            </td>
                        </tr><tr style="margin-bottom: 15px;">
                            <td rowspan="3">Td 1</td>
                            <td>Td 2</td>
                            <td>Td 3</td>
                            <td>Td 4</td>
                            <td>Td 5</td>
                        </tr>
                        <tr>
                            <td>Td 2</td>
                            <td>Td 3</td>
                            <td>Td 4</td>
                            <td>Td 5</td>
                        </tr>
                        <tr>
                            <td colspan="5" style="text-align:right;">
                                <span class="bold" style="margin-right:30px;">Total Present : 6</span>
                                <span class="bold" style="margin-right:30px;">Total Absent : 20</span>
                                <span class="bold">Avg. Working Hours : -4.42 (H)</span>
                            </td>
                        </tr><tr style="margin-bottom: 15px;">
                            <td rowspan="3">Td 1</td>
                            <td>Td 2</td>
                            <td>Td 3</td>
                            <td>Td 4</td>
                            <td>Td 5</td>
                        </tr>
                        <tr>
                            <td>Td 2</td>
                            <td>Td 3</td>
                            <td>Td 4</td>
                            <td>Td 5</td>
                        </tr>
                        <tr>
                            <td colspan="5" style="text-align:right;">
                                <span class="bold" style="margin-right:30px;">Total Present : 6</span>
                                <span class="bold" style="margin-right:30px;">Total Absent : 20</span>
                                <span class="bold">Avg. Working Hours : -4.42 (H)</span>
                            </td>
                        </tr>
                        <tr style="margin-bottom: 15px;">
                            <td rowspan="3">Td 1</td>
                            <td>Td 2</td>
                            <td>Td 3</td>
                            <td>Td 4</td>
                            <td>Td 5</td>
                        </tr>
                        <tr>
                            <td>Td 2</td>
                            <td>Td 3</td>
                            <td>Td 4</td>
                            <td>Td 5</td>
                        </tr>
                        <tr>
                            <td colspan="5" style="text-align:right;">
                                <span class="bold" style="margin-right:30px;">Total Present : 6</span>
                                <span class="bold" style="margin-right:30px;">Total Absent : 20</span>
                                <span class="bold">Avg. Working Hours : -4.42 (H)</span>
                            </td>
                        </tr>
                        <tr style="margin-bottom: 15px;">
                            <td rowspan="3">Td 1</td>
                            <td>Td 2</td>
                            <td>Td 3</td>
                            <td>Td 4</td>
                            <td>Td 5</td>
                        </tr>
                        <tr>
                            <td>Td 2</td>
                            <td>Td 3</td>
                            <td>Td 4</td>
                            <td>Td 5</td>
                        </tr>
                        <tr>
                            <td colspan="5" style="text-align:right;">
                                <span class="bold" style="margin-right:30px;">Total Present : 6</span>
                                <span class="bold" style="margin-right:30px;">Total Absent : 20</span>
                                <span class="bold">Avg. Working Hours : -4.42 (H)</span>
                            </td>
                        </tr>
                        <tr style="margin-bottom: 15px;">
                            <td rowspan="3">Td 1</td>
                            <td>Td 2</td>
                            <td>Td 3</td>
                            <td>Td 4</td>
                            <td>Td 5</td>
                        </tr>
                        <tr>
                            <td>Td 2</td>
                            <td>Td 3</td>
                            <td>Td 4</td>
                            <td>Td 5</td>
                        </tr>
                        <tr>
                            <td colspan="5" style="text-align:right;">
                                <span class="bold" style="margin-right:30px;">Total Present : 6</span>
                                <span class="bold" style="margin-right:30px;">Total Absent : 20</span>
                                <span class="bold">Avg. Working Hours : -4.42 (H)</span>
                            </td>
                        </tr>
                        <tr style="margin-bottom: 15px;">
                            <td rowspan="3">Td 1</td>
                            <td>Td 2</td>
                            <td>Td 3</td>
                            <td>Td 4</td>
                            <td>Td 5</td>
                        </tr>
                        <tr>
                            <td>Td 2</td>
                            <td>Td 3</td>
                            <td>Td 4</td>
                            <td>Td 5</td>
                        </tr>
                        <tr>
                            <td colspan="5" style="text-align:right;">
                                <span class="bold" style="margin-right:30px;">Total Present : 6</span>
                                <span class="bold" style="margin-right:30px;">Total Absent : 20</span>
                                <span class="bold">Avg. Working Hours : -4.42 (H)</span>
                            </td>
                        </tr>
                        <tr style="margin-bottom: 15px;">
                            <td rowspan="3">Td 1</td>
                            <td>Td 2</td>
                            <td>Td 3</td>
                            <td>Td 4</td>
                            <td>Td 5</td>
                        </tr>
                        <tr>
                            <td>Td 2</td>
                            <td>Td 3</td>
                            <td>Td 4</td>
                            <td>Td 5</td>
                        </tr>
                        <tr>
                            <td colspan="5" style="text-align:right;">
                                <span class="bold" style="margin-right:30px;">Total Present : 6</span>
                                <span class="bold" style="margin-right:30px;">Total Absent : 20</span>
                                <span class="bold">Avg. Working Hours : -4.42 (H)</span>
                            </td>
                        </tr>





                       <tr style="margin-bottom: 15px;">
                            <td rowspan="3">Td 1</td>
                            <td>Td 2</td>
                            <td>Td 3</td>
                            <td>Td 4</td>
                            <td>Td 5</td>
                        </tr>
                        <tr>
                            <td>Td 2</td>
                            <td>Td 3</td>
                            <td>Td 4</td>
                            <td>Td 5</td>
                        </tr>
                        <tr>
                            <td colspan="5" style="text-align:right;">
                                <span class="bold" style="margin-right:30px;">Total Present : 6</span>
                                <span class="bold" style="margin-right:30px;">Total Absent : 20</span>
                                <span class="bold">Avg. Working Hours : -4.42 (H)</span>
                            </td>
                        </tr>
                        <tr style="margin-bottom: 15px;">
                            <td rowspan="3">Td 1</td>
                            <td>Td 2</td>
                            <td>Td 3</td>
                            <td>Td 4</td>
                            <td>Td 5</td>
                        </tr>
                        <tr>
                            <td>Td 2</td>
                            <td>Td 3</td>
                            <td>Td 4</td>
                            <td>Td 5</td>
                        </tr>
                        <tr>
                            <td colspan="5" style="text-align:right;">
                                <span class="bold" style="margin-right:30px;">Total Present : 6</span>
                                <span class="bold" style="margin-right:30px;">Total Absent : 20</span>
                                <span class="bold">Avg. Working Hours : -4.42 (H)</span>
                            </td>
                        </tr>
                        <tr style="margin-bottom: 15px;">
                            <td rowspan="3">Td 1</td>
                            <td>Td 2</td>
                            <td>Td 3</td>
                            <td>Td 4</td>
                            <td>Td 5</td>
                        </tr>
                        <tr>
                            <td>Td 2</td>
                            <td>Td 3</td>
                            <td>Td 4</td>
                            <td>Td 5</td>
                        </tr>
                        <tr>
                            <td colspan="5" style="text-align:right;">
                                <span class="bold" style="margin-right:30px;">Total Present : 6</span>
                                <span class="bold" style="margin-right:30px;">Total Absent : 20</span>
                                <span class="bold">Avg. Working Hours : -4.42 (H)</span>
                            </td>
                        </tr>
                        <tr style="margin-bottom: 15px;">
                            <td rowspan="3">Td 1</td>
                            <td>Td 2</td>
                            <td>Td 3</td>
                            <td>Td 4</td>
                            <td>Td 5</td>
                        </tr>
                        <tr>
                            <td>Td 2</td>
                            <td>Td 3</td>
                            <td>Td 4</td>
                            <td>Td 5</td>
                        </tr>
                        <tr>
                            <td colspan="5" style="text-align:right;">
                                <span class="bold" style="margin-right:30px;">Total Present : 6</span>
                                <span class="bold" style="margin-right:30px;">Total Absent : 20</span>
                                <span class="bold">Avg. Working Hours : -4.42 (H)</span>
                            </td>
                        </tr>
                        <tr style="margin-bottom: 15px;">
                            <td rowspan="3">Td 1</td>
                            <td>Td 2</td>
                            <td>Td 3</td>
                            <td>Td 4</td>
                            <td>Td 5</td>
                        </tr>
                        <tr>
                            <td>Td 2</td>
                            <td>Td 3</td>
                            <td>Td 4</td>
                            <td>Td 5</td>
                        </tr>
                        <tr>
                            <td colspan="5" style="text-align:right;">
                                <span class="bold" style="margin-right:30px;">Total Present : 6</span>
                                <span class="bold" style="margin-right:30px;">Total Absent : 20</span>
                                <span class="bold">Avg. Working Hours : -4.42 (H)</span>
                            </td>
                        </tr>
                        <tr style="margin-bottom: 15px;">
                            <td rowspan="3">Td 1</td>
                            <td>Td 2</td>
                            <td>Td 3</td>
                            <td>Td 4</td>
                            <td>Td 5</td>
                        </tr>
                        <tr>
                            <td>Td 2</td>
                            <td>Td 3</td>
                            <td>Td 4</td>
                            <td>Td 5</td>
                        </tr>
                        <tr>
                            <td colspan="5" style="text-align:right;">
                                <span class="bold" style="margin-right:30px;">Total Present : 6</span>
                                <span class="bold" style="margin-right:30px;">Total Absent : 20</span>
                                <span class="bold">Avg. Working Hours : -4.42 (H)</span>
                            </td>
                        </tr>
                        <tr style="margin-bottom: 15px;">
                            <td rowspan="3">Td 1</td>
                            <td>Td 2</td>
                            <td>Td 3</td>
                            <td>Td 4</td>
                            <td>Td 5</td>
                        </tr>
                        <tr>
                            <td>Td 2</td>
                            <td>Td 3</td>
                            <td>Td 4</td>
                            <td>Td 5</td>
                        </tr>
                        <tr>
                            <td colspan="5" style="text-align:right;">
                                <span class="bold" style="margin-right:30px;">Total Present : 6</span>
                                <span class="bold" style="margin-right:30px;">Total Absent : 20</span>
                                <span class="bold">Avg. Working Hours : -4.42 (H)</span>
                            </td>
                        </tr>
                        <tr style="margin-bottom: 15px;">
                            <td rowspan="3">Td 1</td>
                            <td>Td 2</td>
                            <td>Td 3</td>
                            <td>Td 4</td>
                            <td>Td 5</td>
                        </tr>
                        <tr>
                            <td>Td 2</td>
                            <td>Td 3</td>
                            <td>Td 4</td>
                            <td>Td 5</td>
                        </tr>
                        <tr>
                            <td colspan="5" style="text-align:right;">
                                <span class="bold" style="margin-right:30px;">Total Present : 6</span>
                                <span class="bold" style="margin-right:30px;">Total Absent : 20</span>
                                <span class="bold">Avg. Working Hours : -4.42 (H)</span>
                            </td>
                        </tr>
                        <tr style="margin-bottom: 15px;">
                            <td rowspan="3">Td 1</td>
                            <td>Td 2</td>
                            <td>Td 3</td>
                            <td>Td 4</td>
                            <td>Td 5</td>
                        </tr>
                        <tr>
                            <td>Td 2</td>
                            <td>Td 3</td>
                            <td>Td 4</td>
                            <td>Td 5</td>
                        </tr>
                        <tr>
                            <td colspan="5" style="text-align:right;">
                                <span class="bold" style="margin-right:30px;">Total Present : 6</span>
                                <span class="bold" style="margin-right:30px;">Total Absent : 20</span>
                                <span class="bold">Avg. Working Hours : -4.42 (H)</span>
                            </td>
                        </tr>
                        <tr style="margin-bottom: 15px;">
                            <td rowspan="3">Td 1</td>
                            <td>Td 2</td>
                            <td>Td 3</td>
                            <td>Td 4</td>
                            <td>Td 5</td>
                        </tr>
                        <tr>
                            <td>Td 2</td>
                            <td>Td 3</td>
                            <td>Td 4</td>
                            <td>Td 5</td>
                        </tr>
                        <tr>
                            <td colspan="5" style="text-align:right;">
                                <span class="bold" style="margin-right:30px;">Total Present : 6</span>
                                <span class="bold" style="margin-right:30px;">Total Absent : 20</span>
                                <span class="bold">Avg. Working Hours : -4.42 (H)</span>
                            </td>
                        </tr>
                        <tr style="margin-bottom: 15px;">
                            <td rowspan="3">Td 1</td>
                            <td>Td 2</td>
                            <td>Td 3</td>
                            <td>Td 4</td>
                            <td>Td 5</td>
                        </tr>
                        <tr>
                            <td>Td 2</td>
                            <td>Td 3</td>
                            <td>Td 4</td>
                            <td>Td 5</td>
                        </tr>
                        <tr>
                            <td colspan="5" style="text-align:right;">
                                <span class="bold" style="margin-right:30px;">Total Present : 6</span>
                                <span class="bold" style="margin-right:30px;">Total Absent : 20</span>
                                <span class="bold">Avg. Working Hours : -4.42 (H)</span>
                            </td>
                        </tr>
                        <tr style="margin-bottom: 15px;">
                            <td rowspan="3">Td 1</td>
                            <td>Td 2</td>
                            <td>Td 3</td>
                            <td>Td 4</td>
                            <td>Td 5</td>
                            <td>Td 5</td>
                            <td>Td 5</td>
                            <td>Td 5</td>
                            <td>Td 5</td>
                        </tr>
                        <tr>
                            <td>Td 2</td>
                            <td>Td 3</td>
                            <td>Td 4</td>
                            <td>Td 5</td>
                            <td>Td 5</td>
                            <td>Td 5</td>
                        </tr>
                        <tr>
                            <td colspan="5" style="text-align:right;">
                                <span class="bold" style="margin-right:30px;">Total Present : 6</span>
                                <span class="bold" style="margin-right:30px;">Total Absent : 20</span>
                                <span class="bold">Avg. Working Hours : -4.42 (H)</span>
                            </td>
                        </tr>
                        {{--<tr>
                            <td rowspan="3">
                                016<br>
                                Md. Shahin Hossain<br>
                                Executive<br>
                            </td>
                            <td>In</td>
                            <td>10</td>
                            <td>11</td>
                            <td>10</td>
                            <td>11</td>
                            <td>10</td>
                            <td>11</td>
                            <td>11</td>
                            <td>10</td>
                            <td>10</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>In</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>11</td>
                            <td>11</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                        </tr>
                        <tr>
                            <td>Out</td>
                            <td>10</td>
                            <td>11</td>
                            <td>10</td>
                            <td>11</td>
                            <td>10</td>
                            <td>11</td>
                            <td>11</td>
                            <td>10</td>
                            <td>10</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>In</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>11</td>
                            <td>11</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                            <td>10</td>
                            <td>10.20</td>
                        </tr>
                        <tr>
                            <td colspan="30" style="text-align:right;">
                                <span class="bold" style="margin-right:30px;">Total Present : 6</span>
                                <span class="bold" style="margin-right:30px;">Total Absent : 20</span>
                                <span class="bold">Avg. Working Hours : -4.42 (H)</span>
                            </td>
                        </tr>--}}

                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>

<style type="text/css">
    body{
        margin:0;
        font-family: 'Nunito';
    }
    .pdf-wrapper{
    }
    .pdf-head{
        height: 30px;
        background-color: #f2f2f2;
    }
    .pdf_table{
        width: 1240px;
        margin: 0 auto;
        background-color: #fff;
        padding: 10px 5px;
        box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);
        min-height: 29.7cm;
        margin-top:10px;
        margin-bottom:20px;
    }
    .pdf_table table thead{
        background-color:#716ACA;
        color:#ffffff;
    }
    table{
        border-collapse: collapse;
    }
    .table thead tr th{
        border:1px solid #f2f2f2;
        padding:5px 5px;
        font-size: 9px;
    }
    .table tbody tr td{
        border:1px solid #f2f2f2;
        padding:5px;
        font-size: 9px;
    }
</style>