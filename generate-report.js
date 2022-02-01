function printDiv() {
                    var divContents = document.getElementById("myFrame").innerHTML;
                    var a = window.open("", "", "height=924, width=1100");
                    a.document.write("<html>");
                    a.document.write(divContents);
                    a.document.write("</body><style>.print-btn-2{display:none;}#download-btn {display: none;}</style></html>");
                    a.document.close();
                    a.print();
                }
