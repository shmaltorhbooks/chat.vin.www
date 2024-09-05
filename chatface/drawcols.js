function FormDrawColorControl(ColorArray,Name,DefColor,SizeX,EmptySelTextStr)
 {
  var Index;
  
  document.write('<table>');
  document.write('<tr>');

  if (Name == null)
   {
    Name = "";
   }

  if (SizeX == null)
   {
    SizeX = 8;
   }

  if (DefColor == null)
   {
    DefColor = ColorArray[0];
   }

  if (EmptySelTextStr == null)
   {
    EmptySelTextStr = "";
   }

  for (Index = 0;Index < ColorArray.length;Index++)
   {
    document.write('<td BGCOLOR=\"'+ColorArray[Index]+'\">');
    document.write('<input type=radio name=\"'+Name+'\"');
    document.write(' value=\"'+ColorArray[Index]+'\"');

    if (ColorArray[Index] == DefColor)
     {
      document.write(' CHECKED');
     }

    document.write('>');
    document.write('</td>');

    if (((Index+1) % SizeX) == 0)
     {
      if ((Index+1) < ColorArray.length)
       {
        document.write('</tr>');
        document.write('<tr>');
       }
     }
   }

  document.write('</tr>');

  if (EmptySelTextStr != "")
   {
    document.write('<tr>');
    document.write('<td colspan=');

    if (SizeX > ColorArray.length)
     {
      document.write(SizeX);
     }
    else
     { 
      document.write(ColorArray.length);
     }

    document.write('>');

    document.write('<input type=radio name=\"'+Name+'\"');
    document.write(' value=\"\"');

    if (DefColor == "")
     {
      document.write(' CHECKED');
     }

    document.write('>');

    document.write(EmptySelTextStr);

    document.write('</td>');
    document.write('</tr>');
   }

  document.write('</table>');
  document.writeln('');
 }
