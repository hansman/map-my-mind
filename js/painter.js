//Johann Steinbrecher
//Santa Clara 2012


var states = [
	{
		'name':'idle',
		'initial':true,
		'events':
		{
			'clickOnCanvas':'addReference',
			'clickOnReference':'dragReference',
			'clickOnNode':'createStartNode',
			'deletePressed':'deleteObject',
			'zoomWheel':'zoomState',
			'clickMousewheel':'panState'
		}
	},
	{
		'name':'addReference',
		'events':
		{
			'backToIdle':'idle'
		}
	},
	{
		'name':'dragReference',
		'events':
		{
			'mouseUp':'endDragReference'
		}
	},
	{
		'name':'endDragReference',
		'events':
		{
		   'backToIdle':'idle'
		}
	
	},
	{
		'name':'createStartNode',
		'events':
		{
		   'proceedToDrawLine':'drawLine'
		}
	
	},
	{
		'name':'drawLine',
		'events':
		{
		   'clickOnNode':'addConnection',
		   'clickOnCanvas':'idle'
		}
	
	},
	{
		'name':'addConnection',
		'events':
		{
		   'backToIdle':'idle'
		}	
	},
	{
		'name':'deleteObject',
		'events':
		{
		   'backToIdle':'idle'
		}	
	},
	{
		'name':'zoomState',
		'events':
		{
		   'backToIdle':'idle'
		}	
	},
	{
		'name':'panState',
		'events':
		{
		   'mouseUp':'idle'
		}	
	}
];






function StateMachine(states){

	this.states = states;
	
	
	
	this.indexes = {}; 
	for( var i in this.states)
	{
		this.indexes[this.states[i].name] = i;
		if (this.states[i].initial)
		{
			this.previousState = this.currentState;
			this.currentState = this.states[i];
		}
	}
	
	
	this.consumeEvent = function(e)
	{
		if(this.currentState.events[e])
		{
			this.previousState = this.currentState;
			this.currentState = this.states[this.indexes[this.currentState.events[e]]] ;
		}
	};
	
	this.getStatus = function()
	{
		return this.currentState.name;
	};
	
}


Function.prototype.bind = function(obj)
{
	var fn = this;
	return function()
	{
		return fn.apply(obj, arguments);
	};
};


var SinglePoint = function(x,y)
{
	this.x =x;
	this.y =y;
};


var Rectangle = function(x, y, width, height)
{
	this.x = x;
	this.y = y;
	this.width = width;
	this.height = height;
};



Rectangle.prototype.areacontains = function(testpoint)
{
	return ( (testpoint.x >= this.x-3) && (testpoint.x <= (this.x + this.width+3)) && (testpoint.y >= this.y-3) && (testpoint.y <= (this.y + this.height + 3 )));
};

Rectangle.prototype.contains = function(testpoint)
{
	return ( (testpoint.x >= this.x) && (testpoint.x <= (this.x + this.width)) && (testpoint.y >= this.y) && (testpoint.y <= (this.y + this.height )));
};


var Reference = function(targetpoint,title,authorandyear,theMindMap)
{
  this.drag=false;
  this.authorandyear=authorandyear;
  this.title=title;
  this.line1;
  this.line2;
  this.nodesize=6;
  this.radius=16;
  this.rectangle = new Rectangle(targetpoint.x,targetpoint.y,theMindMap.currentwidth,theMindMap.currentheight); 
  this.node = new Array(4);
  this.node[0] = new Node(targetpoint.x- this.nodesize/2,targetpoint.y + theMindMap.currentheight/2 - this.nodesize/2,this.nodesize,this.nodesize); 
  this.node[1] = new Node(targetpoint.x+theMindMap.currentwidth-this.nodesize/2,targetpoint.y + theMindMap.currentheight/2 - this.nodesize/2 ,this.nodesize,this.nodesize);
  this.node[2] = new Node(targetpoint.x + theMindMap.currentwidth/2 - this.nodesize/2,targetpoint.y- this.nodesize/2,this.nodesize,this.nodesize);
  this.node[3] = new Node(targetpoint.x + theMindMap.currentwidth/2 - this.nodesize/2,targetpoint.y + theMindMap.currentwidth/2 - this.nodesize/2,this.nodesize,this.nodesize);  
  
  this.selected=false;
  
  this.oldPosition=targetpoint;

};



Reference.prototype.zoom = function(delta, mouse)
{

    var newwidth = this.rectangle.width*(1+delta);
    var newheight = this.rectangle.height*(1+delta);

    var deltax = (this.rectangle.x + newwidth/2 - mouse.x)*(1+delta);
    var deltay = (this.rectangle.y + newheight/2 - mouse.y)*(1+delta);

    this.rectangle.x = mouse.x + deltax - newwidth/2;
    this.rectangle.y = mouse.y + deltay - newheight/2; 

    this.node[0].rectangle.x = this.rectangle.x -3;
    this.node[0].rectangle.y = this.rectangle.y + newheight/2 - 3;
    
    this.node[1].rectangle.x = this.rectangle.x + newwidth - 3;
    this.node[1].rectangle.y = this.rectangle.y + newheight/2 - 3;
     
    this.node[2].rectangle.x = this.rectangle.x + newwidth/2 - 3;
    this.node[2].rectangle.y = this.rectangle.y -3;
    
    this.node[3].rectangle.x = this.rectangle.x + newwidth/2 - 3;
    this.node[3].rectangle.y = this.rectangle.y + newheight -3;
    
	this.rectangle.width = newwidth;
	this.rectangle.height = newheight;

};

Reference.prototype.paint = function(theMindMap)
{
	
	theMindMap.context.lineWidth = 1;
	theMindMap.context.fillStyle = "#ffffff";
	if(this.selected  )
		{theMindMap.context.strokeStyle = "#FF0000";}
 	else
 		{theMindMap.context.strokeStyle = "#000000";}
 		
	theMindMap.roundedRect(this.rectangle.x, this.rectangle.y , this.rectangle.width , this.rectangle.height , 16, this.title );
		
    if(this.selected)
    {
     for (var j in this.node)
     {
    	this.node[j].paint(theMindMap);
     }
    }
    
    

};


var Node = function(x,y,width,height)
{
   this.selected=false;
   this.rectangle = new Rectangle(x,y,width,height);
   
};


Node.prototype.paint = function(theMindMap)
{
   theMindMap.context.lineWidth = 1;
   if(this.selected==true)
   	{theMindMap.context.fillStyle = "#00FF00";}
   else
   	{theMindMap.context.fillStyle = "#FF0000";}
   	
   	
   theMindMap.context.strokeStyle = "#FF0000";
   theMindMap.context.fillRect(this.rectangle.x , this.rectangle.y , this.rectangle.width , this.rectangle.height );
   theMindMap.context.strokeRect(this.rectangle.x , this.rectangle.y , this.rectangle.width, this.rectangle.height);
};


var Connection = function(from,to)
{
  this.from = from;
  this.to = to;
  
  this.selected=false;
};



Connection.prototype.paint = function(theMindMap)
{
  	theMindMap.context.lineWidth = 1;
	theMindMap.context.fillStyle = "#ffffff";
	
	if(this.selected)
		theMindMap.context.strokeStyle = "#FF0000";
	else
		theMindMap.context.strokeStyle = "#000000";
	
	theMindMap.context.beginPath();
	theMindMap.context.moveTo(this.from.rectangle.x+3 , this.from.rectangle.y+3);
	theMindMap.context.lineTo(this.to.rectangle.x+3,this.to.rectangle.y+3);
	theMindMap.context.closePath();
	theMindMap.context.stroke();
};

Connection.prototype.contains = function(testpoint)
{
	var linederivation = ( this.to.rectangle.x+3 - (this.from.rectangle.x+3) ) / ( this.to.rectangle.y +3 - (this.from.rectangle.y+3) );
	var testderivation = ( testpoint.x +3 - (this.from.rectangle.x+3) ) / (testpoint.y +3 - (this.from.rectangle.y+3));
	
	if( (  testderivation - linederivation < 0.3 ) && (  testderivation - linederivation > -0.3 )  )
		return true;
 	else
 		return false;	
};


var MindMap = function(theCanvas)
{
    this.canvas = theCanvas;
    this.canvas.focus();
    this.defaultdisplay = true;
    this.mousePosition = new SinglePoint(0, 0);
    this.startNode = new Node(0,0,0,0,0);
    
    this.zoomdelta = 0;
    this.currentwidth=200;
    this.currentheight=100;
    
    this.draghelper=false;
    
    this.current='reference';
    
	this.references = [];
	this.connections = [];
	this.context = this.canvas.getContext("2d");
	this.settings = { background: "#fff", connection: "#000", selection: "#000", node: "#31456b", nodeBorder: "#fff", nodeHoverBorder: "#000", nodeHover: "#0c0" };
  
  
  	this.mouseDownHandler = this.mouseDown.bind(this);
	this.mouseUpHandler = this.mouseUp.bind(this);
	this.mouseMoveHandler = this.mouseMove.bind(this);
	
	this.touchStartHandler = this.touchStart.bind(this);
	this.touchEndHandler = this.touchEnd.bind(this);
	this.touchMoveHandler = this.touchMove.bind(this);
	
	this.keyHandler = this.key.bind(this);
	
	this.mousewheelHandler = this.mousewheel.bind(this);

	this.canvas.addEventListener("mousedown", this.mouseDownHandler, false);
	this.canvas.addEventListener("mouseup", this.mouseUpHandler, false);
	this.canvas.addEventListener("mousemove", this.mouseMoveHandler, false);
	
	this.canvas.addEventListener("touchstart", this.touchStartHandler, false);
	this.canvas.addEventListener("touchend", this.touchEndHandler, false);
	this.canvas.addEventListener("touchmove", this.touchMoveHandler, false);
	
	this.canvas.addEventListener("keydown", this.keyHandler, false);
	
	this.canvas.addEventListener("DOMMouseScroll",this.mousewheelHandler,false);
	this.canvas.addEventListener("mousewheel",this.mousewheelHandler,false);

};






MindMap.prototype.performState = function()
{

   this.canvas.style.background = this.settings.background;
   this.context.clearRect(0, 0, this.canvas.width, this.canvas.height);
   
   
   switch ( sm.getStatus() ) 
   {
    case "idle": 	
    				var text;
    				if ( document.getElementById("paper").value )
    				{
    					text = document.getElementById("paper").value;
    				}
    				else
    				{
    				    text = "";
    				}
   					this.roundedRect(this.mousePosition.x - this.currentwidth/2, this.mousePosition.y - this.currentheight/2 , this.currentwidth , this.currentheight , 16, text );
					this.renderMap();  
    				
                   break;
 
    case "addReference": 	if( document.getElementById("paper").value!="" )
    						{
    						  if( !this.checkNames( document.getElementById("paper").value ) )
    						  {
    							  
    							  
    							  
    						  var tempPoint = new SinglePoint(this.mousePosition.x - this.currentwidth/2 , this.mousePosition.y - this.currentheight/2 );
							  this.references.push(new Reference(tempPoint, document.getElementById("paper").value , document.getElementById("paper").value, this));
							
							  document.getElementById("paper").value="";
							
							  this.literaturelist();
    						  }
    						}
    						this.renderMap();
    						sm.consumeEvent('backToIdle');
                     		break;
                     		
    case "dragReference":		
    						for (var j in this.references)
    						{
    					 	  if(this.references[j].drag)
							  {	
							  
							    var deltax = 0;
							    var deltay = 0;
 							    if(!this.draghelper)
								{
									deltax = this.mousePosition.x - this.references[j].oldPosition.x;
     							    deltay = this.mousePosition.y - this.references[j].oldPosition.y;
     							    
     							}
     							this.draghelper=false;
     							
								this.references[j].rectangle.x += deltax;
								this.references[j].rectangle.y += deltay;
								
								for (var i in this.references[j].node)
    							{
									this.references[j].node[i].rectangle.x += deltax;
									this.references[j].node[i].rectangle.y += deltay;
    							}		
			
								this.references[j].oldPosition=this.mousePosition;
								break;
								
							  }
							}
							
    						this.renderMap();
    
    						 
							break;
							
							
	case "endDragReference": for (var j in this.references)
    						{  						
    						  this.references[j].drag=false;   
    						}
    						
	                        this.renderMap();
    
    						 
    						sm.consumeEvent('backToIdle');
    						
							break;
							
	case "createStartNode":	for (var j in this.references)
    						{
								for (var i in this.references[j].node)
								{
									if(this.references[j].node[i].selected)
									{
										this.startNode=this.references[j].node[i];
										sm.consumeEvent('proceedToDrawLine');
										break;
									}
								
								}
    						}
			
	case "drawLine":		this.context.lineWidth = 1;
							this.context.fillStyle = "#ffffff";
							this.context.strokeStyle = "#000000";
							this.context.beginPath();
							this.context.moveTo(this.startNode.rectangle.x +3 , this.startNode.rectangle.y +3);
							this.context.lineTo(this.mousePosition.x,this.mousePosition.y);
							this.context.closePath();
							this.context.stroke(); 
							
							this.renderMap();
    
    						
							
    						break;
    						
    case "addConnection":   
    						for (var j in this.references)
    						{
								for (var i in this.references[j].node)
								{
									if(this.references[j].node[i].selected)
									{
										this.connections.push( new Connection(this.startNode,this.references[j].node[i]));
										sm.consumeEvent('backToIdle');
									}
								}
    						}
    						
    						this.renderMap();
    						break;
    						
    						
    case "deleteObject":	var refkey=null;
    						var delflag=true;
    	
    						for(refkey in this.references) 
    						{
    							if(this.references[refkey].selected)
    						    {      								
    								break;
    						    }
    						}						
    						
    						while(delflag)
    						{
    						 delflag=false;
    						 for (var j in this.connections)
    						 {
    							for (var i in this.references[refkey].node)
    							{
    							   if( (this.references[refkey].node[i] == this.connections[j].from) || (this.references[refkey].node[i] == this.connections[j].to) )	
    							   {   
    								   delflag=true;
    							   }
    							}
    							
    							if(delflag)
    							{
    								this.connections.splice(j,1);
    								break;
    							}
    						 }
    						}
    						
    						this.references.splice(refkey,1);
   							this.renderMap();    
    						sm.consumeEvent('backToIdle');
    						break; 
    						
    case "zoomState":		//alert("zoom value "+this.zoomdelta);
    						if( (this.currentheight <= 36) && (this.zoomdelta<0) )
    						{}
    						else
    						{
							  this.currentwidth = this.currentwidth*(1+this.zoomdelta/10);
							  this.currentheight = this.currentheight*(1+this.zoomdelta/10);
	    
    						  for (var j in this.references)
    						  {
								this.references[j].zoom(this.zoomdelta/10, this.mousePosition);
    						  }
    						
    						  
    						}
    						this.roundedRect(this.mousePosition.x - this.currentwidth/2, this.mousePosition.y - this.currentheight/2 , this.currentwidth , this.currentheight , 16, document.getElementById("paper").value );
   							this.renderMap();
    
    						sm.consumeEvent('backToIdle');
    						break; 
    						
    case "panState":		
    	                    if(this.references[0])
    	                    {
    						  var deltax = 0;
							  var deltay = 0;
 							  if(!this.draghelper)
							  {
								deltax = this.mousePosition.x - this.references[0].oldPosition.x;
   							    deltay = this.mousePosition.y - this.references[0].oldPosition.y;
   							  }
   							  this.draghelper=false;
							
							  for (var j in this.references)
    						  {
    							this.references[j].rectangle.x += deltax;
								this.references[j].rectangle.y += deltay;
								for (var i in this.references[j].node)
								{
									this.references[j].node[i].rectangle.x += deltax;
									this.references[j].node[i].rectangle.y += deltay;
								}
    						  }
							
							  this.references[0].oldPosition=this.mousePosition;
    	                    }
							this.renderMap();
    						break;
    
 
    default: alert('default state');
   }

};


MindMap.prototype.mousewheel = function(e)
{
  	
	e.preventDefault();
	this.zoomdelta = e.wheelDelta ? e.wheelDelta/40 : e.detail ? -e.detail : 0;

    sm.consumeEvent('zoomWheel');

    mindmap.performState();
	
	this.stopEvent(e);
	return null;
};



MindMap.prototype.mouseDown = function(e)
{
	e.preventDefault();
	this.updateMousePosition(e);
	
	if(e.which==1)   //left button
	{
		
  	 var objectClicked = false;
	
	 //check if an object got clicked
	 for (var j in this.references)
     {
    
       for (var i in this.references[j].node)
       {
        if(this.references[j].node[i].selected==true)
    	{
    	    objectClicked=true;
    	    sm.consumeEvent('clickOnNode');   	    
    	    break;
    	 }
       }
       
       //check references
	   if(this.references[j].selected==true && !objectClicked)
	   {
	      objectClicked=true;document.getElementById("paper").value;
	      this.references[j].drag=true;
	      sm.consumeEvent('clickOnReference');
	      this.draghelper=true;
	      for (var j in this.connections)
			{
				this.connections[j].paint(this);
			}
				
		  for (var j in this.references)
			{
				this.references[j].paint(this);
			} 
	   }
    }
	
	if(objectClicked==false)
	{
		sm.consumeEvent('clickOnCanvas');
	}		
   }
   else if(e.which == 2)  //mousewheel
   {
      this.draghelper=true;
      sm.consumeEvent('clickMousewheel');
   }
   else
   {
     this.stopEvent(e);
	 return null;
   }
   
   
   
 	 mindmap.performState();
	
	 this.stopEvent(e);
	 return null;
	
};



MindMap.prototype.mouseUp = function(e)
{
	e.preventDefault(); 
	
    
    sm.consumeEvent('mouseUp');
    this.performState();
    
    
    this.stopEvent(e);
};

MindMap.prototype.mouseMove = function(e)
{
	this.canvas.focus();
  	e.preventDefault(); 
	this.updateMousePosition(e);
	
	var flag = false;

	//mark selected objects
	for (var j in this.references)
    {
		if(this.references[j].rectangle.areacontains(this.mousePosition))
		{
		    if(this.references[j].rectangle.contains(this.mousePosition))
		    {
				this.references[j].selected=true;
				flag=true;
			}
		    else
		 	{
				this.references[j].selected=false;			
			}
			
			for (var i in this.references[j].node)
    		{
    		  if (this.references[j].node[i].rectangle.contains(this.mousePosition))
    		  {  
    		    this.references[j].node[i].selected=true;
    		    flag=true;
    		  }
    		  else
    		  {
    		    this.references[j].node[i].selected=false;    		  
    		  }
			}		    
		}
		else
		{
			this.references[j].selected=false;
			for (var i in this.references[j].node)
    		{			
    			this.references[j].node[i].selected=false;
    		}
		}
						
    }
    
    if(!flag)
    {
    	for (var j in this.connections)
    	{
    		if(this.connections[j].contains(this.mousePosition))
    			this.connections[j].selected=true;
    		else
    			this.connections[j].selected=false;
    	}
    }
    
	
	this.performState();
	this.stopEvent(e); 
};

//check if this paper is already in the mind map
MindMap.prototype.checkNames = function( papername )
{
	for (var i in this.references)
	{			
		if(this.references[i].title==papername)
			return true;
	}
	return false;
};

	
MindMap.prototype.touchStart = function(e)
{

  this.stopEvent(e);
};

MindMap.prototype.touchEnd = function(e)
{

  this.stopEvent(e);
};

MindMap.prototype.touchMove = function(e)
{

  this.stopEvent(e);
};


MindMap.prototype.key = function(e)
{
	if ( (e.keyCode == 46) || (e.keyCode == 8)  ) 
	{	
		sm.consumeEvent('deletePressed');
	}	
	
	this.performState(); 
	this.stopEvent(e);

};

MindMap.prototype.stopEvent = function(e)
{
	e.preventDefault();
	e.stopPropagation();
};


MindMap.prototype.updateMousePosition = function(e)
{
	this.shiftKey = e.shiftKey;
	this.mousePosition = new SinglePoint(e.pageX - this.canvas.offsetLeft, e.pageY - this.canvas.offsetTop);
};

MindMap.prototype.attachReference = function()
{
    
	this.updateMouseAttachment();
	for (var j in this.connections)
	{
		this.connections[j].paint(this);
	}
		
		for (var j in this.references)
	{
		this.references[j].paint(this);
	} 
};


MindMap.prototype.dispose = function()
{
	if (this.canvas !== null)
	{
		this.canvas.removeEventListener("mousedown", this.mouseDownHandler);
		this.canvas.removeEventListener("mouseup", this.mouseUpHandler);
		this.canvas.removeEventListener("mousemove", this.mouseMoveHandler);
		this.canvas.removeEventListener("touchstart", this.touchStartHandler);
		this.canvas.removeEventListener("touchend", this.touchEndHandler);
		this.canvas.removeEventListener("touchmove", this.touchMoveHandler);
		this.canvas.removeEventListener("key", this.keyHandler);	
		this.canvas = null;
		this.context = null;
	}
};


MindMap.prototype.roundedRect = function(x,y,width,height,radius, text )
{
	this.context.lineWidth = 1;
	this.context.fillStyle = "#ffffff";
	this.context.strokeStyle = "#000000";
	 
	this.context.beginPath();
	this.context.moveTo(x + radius, y);
	this.context.lineTo(x + width - radius, y);
	this.context.quadraticCurveTo(x + width, y, x + width, y + radius);
	this.context.lineTo(x + width, y + height - radius);
	this.context.quadraticCurveTo(x + width, y + height, x + width - radius, y + height);
	this.context.lineTo(x + radius, y + height);
	this.context.quadraticCurveTo(x, y + height, x, y + height - radius);
	this.context.lineTo(x, y + radius);
	this.context.quadraticCurveTo(x, y, x + radius, y);
	this.context.closePath();
	this.context.fill();
	this.context.stroke();
	
	if(text)
	{
 	  var textsize=width/15;
	  var temp = text.split(",")[0];
	  var etal="";
	  if(temp[1])
	  {
  		 etal = " et al.";
	  }
	  
	  var author = temp.split(".")[0] + "." + temp.split(".")[1] + etal;
	  var year = text.split(".  ")[1];
	  var title = text.split(".  ")[2];
	
	  this.context.fillStyle = "blue";
	  this.context.font = "italic "+textsize+"pt Arial";
      this.context.lineWidth = 1;
	  this.context.fillText( author , x + width/2 - this.context.measureText(author).width/2, y+height/6);
	
	  this.context.fillStyle = "blue";
	  this.context.font = "italic "+textsize+"pt Arial";
      this.context.lineWidth = 1;
	  this.context.fillText( year , x + width/2 - this.context.measureText(year).width/2, y+height/3);

	  title = "\""+title+"\"";
	  textsize=width/20;
      this.context.fillStyle = 'green';
      this.context.font = "italic "+textsize+"pt Calibri";
      this.context.lineWidth = 1;
    	
      var wc = title.split(" ");
      var line = "";
      var lineheight=0;
      //console.log(wc.length);
        
      for(var i = 0; i < wc.length; i++) 
      {
        var test = line + wc[i] + " ";
            
        if(this.context.measureText(test).width > width * 0.98 ) 
        { 
          if( lineheight < height/2 )
          {
            this.context.fillText(line, x + width/2 - this.context.measureText(line).width/2, y + height/2 + lineheight);
            line = wc[i] + " ";
            lineheight += textsize+5;
            
          }
        }
        else
        {   
       	  line = test;
        }
      }
      if( lineheight < height/2 )
      {
    	  this.context.fillText(line, x + width/2 - this.context.measureText(line).width/2, y + height/2 + lineheight);
      }
	}
	else
	{
		  this.context.fillStyle = "orange";
		  var textsize=width/20;
		  this.context.font = "italic "+textsize+"pt Arial";
	      this.context.lineWidth = 1;
	      var string = "Please select a paper ...";
		  this.context.fillText( string , x + width/2 - this.context.measureText(string).width/2, y+height/6);
	}
};

MindMap.prototype.renderMap = function()
{
	for (var j in this.connections)
	{
		this.connections[j].paint(this);
	}
	
	for (var j in this.references)
	{
		this.references[j].paint(this);
	}
};

MindMap.prototype.literaturelist = function()
{
	document.getElementById("thelist").value="";
	var string="";
	var j;
	for (var i in this.references)
	{   
		if( (j=this.getPaperIndex(this.references[i].title.split(".  ")[2]) ) >=0 )
		{
			  string += jsonPapers[j].author + ", " +  jsonPapers[j].title + ", ";
			  if(jsonPapers[j].publisher)
				string += jsonPapers[j].publisher + ", ";
			  if(jsonPapers[j].month != 0)
					string += jsonPapers[j].month + "\/";
			  string += jsonPapers[j].date + ", ";
			  if( jsonPapers[j].volume )
				string += "volume " + jsonPapers[j].volume + ", ";
			  if( jsonPapers[j].startpage && jsonPapers[j].lastpage )
				string += "pp. " + jsonPapers[j].startpage + "-" + jsonPapers[j].lastpage + ", ";
			  if( jsonPapers[j].doi )
				string += "doi:" + jsonPapers[j].doi;
			  
			  
			  string += "\n";
		}
	}
	document.getElementById("thelist").value = string;
};

MindMap.prototype.getPaperIndex = function(title)
{
	for (var j in jsonPapers)
		if ( jsonPapers[j].title==title )
			
			return j;
	
	return -1;
};



MindMap.prototype.createJsonObject = function()
{
	/*for (var j = 0; j < jsonPapers.length; j++)
		if ( jsonPapers[j].title==title )
			
			return j;
	
	return -1; */
	return { "reference": {
		           "0":{
		        	   "title":"firsttitle",
		        	   "position":"theposition"
		               },
		           "1":{
			           "title":"sndtitle",
			           "position":"the2position"
			           }	            
				}, 
			 "connection": {"name": "somename"},
			 "metadata": {"zoom": "15"}
	};
};