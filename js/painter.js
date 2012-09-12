//Johann Steinbrecher
//Santa Clara 2012

var nsize = 3;   //Node size
var refw = 200;
var refh = 100;

var StateMachine = function () {

	this.states = [
	           	{
	        		'name':'idle',
	        		'initial':true,
	        		'events':
	        		{
	        			'clickOnCanvas':'addRef',
	        			'clickOnRef':'dragRef',
	        			'clickOnNode':'createStartNode',
	        			'deletePressed':'deleteObject',
	        			'zoomWheel':'zoomState',
	        			'clickMousewheel':'panState'
	        		}
	        	},
	        	{
	        		'name':'addRef',
	        		'events':
	        		{
	        			'backToIdle':'idle'
	        		}
	        	},
	        	{
	        		'name':'dragRef',
	        		'events':
	        		{
	        			'mouseUp':'endDragRef'
	        		}
	        	},
	        	{
	        		'name':'endDragRef',
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
	        		   'clickOnNode':'addCon',
	        		   'clickOnCanvas':'idle'
	        		}
	        	
	        	},
	        	{
	        		'name':'addCon',
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
};



StateMachine.prototype.consumeEvent = function(e)
{
	if(this.currentState.events[e])
	{
		this.previousState = this.currentState;
		this.currentState = this.states[this.indexes[this.currentState.events[e]]] ;
	}
};

StateMachine.prototype.getStatus = function()
{
	return this.currentState.name;
};


Function.prototype.bind = function(obj)
{
	var fn = this;
	return function()
	{
		return fn.apply(obj, arguments);
	};
};


var Point = function(x,y)
{
	this.x =x;
	this.y =y;
};


var Rect = function(x, y, w, h)
{
	this.x = x;
	this.y = y;
	this.width = w;
	this.height = h;
};



Rect.prototype.areacontains = function(p)
{
	return ( (p.x >= this.x-nsize) && (p.x <= (this.x + this.width+nsize)) && (p.y >= this.y-nsize) && (p.y <= (this.y + this.height + nsize )));
};

Rect.prototype.contains = function(p)
{
	return ( (p.x >= this.x) && (p.x <= (this.x + this.width)) && (p.y >= this.y) && (p.y <= (this.y + this.height )));
};


var Ref = function(start,title,map)
{
  this.drag=false;
  this.title=title;
  this.radius=16;
  this.frame = new Rect(start.x,start.y,map.livew,map.liveh); 
  this.node = [4];
  this.node[0] = new Node(start.x- nsize,start.y + map.liveh/2 - nsize,nsize*2,nsize*2); 
  this.node[1] = new Node(start.x+map.livew-nsize,start.y + map.liveh/2 - nsize ,nsize*2,nsize*2);
  this.node[2] = new Node(start.x + map.livew/2 - nsize,start.y- nsize,nsize*2,nsize*2);
  this.node[3] = new Node(start.x + map.livew/2 - nsize,start.y + map.livew/2 - nsize,nsize*2,nsize*2);  
  
  this.sel=false;  
  this.oldstart=start;

};



Ref.prototype.zoom = function(delta, mouse)
{

    var newwidth = this.frame.width*(1+delta);
    var newheight = this.frame.height*(1+delta);

    var deltax = (this.frame.x + newwidth/2 - mouse.x)*(1+delta);
    var deltay = (this.frame.y + newheight/2 - mouse.y)*(1+delta);

    this.frame.x = mouse.x + deltax - newwidth/2;
    this.frame.y = mouse.y + deltay - newheight/2; 
	this.frame.width = newwidth;
	this.frame.height = newheight;

    this.node[0].frame.x = this.frame.x - nsize;
    this.node[0].frame.y = this.frame.y + newheight/2 - nsize;    
    this.node[1].frame.x = this.frame.x + newwidth - nsize;
    this.node[1].frame.y = this.frame.y + newheight/2 - nsize;     
    this.node[2].frame.x = this.frame.x + newwidth/2 - nsize;
    this.node[2].frame.y = this.frame.y -nsize;    
    this.node[3].frame.x = this.frame.x + newwidth/2 - nsize;
    this.node[3].frame.y = this.frame.y + newheight -nsize;
};

Ref.prototype.paint = function(map)
{
	
	map.ctxt.lineWidth = 1;
	map.ctxt.fillStyle = "#ffffff";
	if(this.sel  )
		 map.ctxt.strokeStyle = "#FF0000";
 	else
 		 map.ctxt.strokeStyle = "#000000";
 		
	map.roundedRect(this.frame.x, this.frame.y , this.frame.width , this.frame.height , 16, this.title );
		
    if(this.sel)
      for (var j in this.node)
    	this.node[j].paint(map);   
    

};


var Node = function(x,y,width,height)
{
   this.sel=false;
   this.frame = new Rect(x,y,width,height);   
};


Node.prototype.paint = function(map)
{
   map.ctxt.lineWidth = 1;
   if(this.sel==true)
   	map.ctxt.fillStyle = "#00FF00";
   else
   	map.ctxt.fillStyle = "#FF0000";   	
   	
   map.ctxt.strokeStyle = "#FF0000";
   map.ctxt.fillRect(this.frame.x , this.frame.y , this.frame.width , this.frame.height );
   map.ctxt.strokeRect(this.frame.x , this.frame.y , this.frame.width, this.frame.height);
};


var Con = function(from,to)
{
  this.from = from;
  this.to = to;  
  this.sel=false;
};



Con.prototype.paint = function(map)
{
	map.ctxt.lineWidth = 1;
	map.ctxt.fillStyle = "#ffffff";
	
	if(this.sel)
		map.ctxt.strokeStyle = "#FF0000";
	else
		map.ctxt.strokeStyle = "#000000";
	
	map.ctxt.beginPath();
	map.ctxt.moveTo(this.from.frame.x + nsize , this.from.frame.y + nsize);
	map.ctxt.lineTo(this.to.frame.x + nsize,this.to.frame.y + nsize);
	map.ctxt.closePath();
	map.ctxt.stroke();
};

Con.prototype.contains = function(p)
{
	var linederivation = ( this.to.frame.x + nsize - (this.from.frame.x + nsize) ) / ( this.to.frame.y + nsize - (this.from.frame.y + nsize) );
	var testderivation = ( p.x + nsize - (this.from.frame.x + nsize) ) / (p.y + nsize - (this.from.frame.y + nsize));
	
	if( (  testderivation - linederivation < 0.3 ) && (  testderivation - linederivation > - 0.3 )  )
		return true;
 	else
 		return false;	
};


var MindMap = function(theCanvas)
{
    this.canvas = theCanvas;
    this.canvas.focus();
    this.ctxt = this.canvas.getContext("2d");
    this.defaultdisplay = true;
    this.mousePosition = new Point(0, 0);
    this.startNode = new Node(0,0,0,0,0);
    
    this.zoomdelta = 0;
    this.livew=refw;
    this.liveh=refh;
    
    this.draghelper=false;
    
	this.refs = [];
	this.cons = [];
	this.settings = { background: "#fff"};
  
  
  	this.mouseDownHandler = this.mouseDown.bind(this);
	this.mouseUpHandler = this.mouseUp.bind(this);
	this.mouseMoveHandler = this.mouseMove.bind(this);	
	this.keyHandler = this.key.bind(this);	
	this.mousewheelHandler = this.mousewheel.bind(this);

	this.canvas.addEventListener("mousedown", this.mouseDownHandler, false);
	this.canvas.addEventListener("mouseup", this.mouseUpHandler, false);
	this.canvas.addEventListener("mousemove", this.mouseMoveHandler, false);	
	this.canvas.addEventListener("keydown", this.keyHandler, false);	
	this.canvas.addEventListener("DOMMouseScroll",this.mousewheelHandler,false);
	this.canvas.addEventListener("mousewheel",this.mousewheelHandler,false);

};

MindMap.prototype.performState = function()
{

   this.canvas.style.background = this.settings.background;
   this.ctxt.clearRect(0, 0, this.canvas.width, this.canvas.height);
   
   
   switch ( sm.getStatus() ) 
   {
    case "idle": 	
    				var text;
    				if ( document.getElementById("paper").value )
    					text = document.getElementById("paper").value;
    				else
    				    text = "";
   					this.roundedRect(this.mousePosition.x - this.livew/2, this.mousePosition.y - this.liveh/2 , this.livew , this.liveh , 16, text );
					this.renderMap();  
    				
                    break;
 
    case "addRef": 			if( document.getElementById("paper").value!="" )
    						{
    						  if( !this.checkNames( document.getElementById("paper").value ) )
    						  {      							  
    							  var tempPoint = new Point(this.mousePosition.x - this.livew/2 , this.mousePosition.y - this.liveh/2 );
    							  this.refs.push(new Ref(tempPoint, document.getElementById("paper").value, this));
    							  document.getElementById("paper").value="";
    							  this.literaturelist();
    						  }
    						}
    						this.renderMap();
    						sm.consumeEvent('backToIdle');
                     		break;
                     		
    case "dragRef": 		for (var j in this.refs)
    						{
    					 	  if(this.refs[j].drag)
							  {								  
							    var deltax = 0;
							    var deltay = 0;
 							    if(!this.draghelper)
								{
									deltax = this.mousePosition.x - this.refs[j].oldstart.x;
     							    deltay = this.mousePosition.y - this.refs[j].oldstart.y;     							    
     							}
 							    
     							this.draghelper=false;     							
								this.refs[j].frame.x += deltax;
								this.refs[j].frame.y += deltay;
								
								for (var i in this.refs[j].node)
    							{
									this.refs[j].node[i].frame.x += deltax;
									this.refs[j].node[i].frame.y += deltay;
    							}		
			
								this.refs[j].oldstart=this.mousePosition;
								break;
								
							  }
							}
							
    						this.renderMap();    						 
							break;							
							
	case "endDragRef": 		for (var j in this.refs)					
    						  this.refs[j].drag=false;   
    						
	                        this.renderMap();    						 
    						sm.consumeEvent('backToIdle');    						
							break;
							
	case "createStartNode":	for (var j in this.refs)
    						{
								for (var i in this.refs[j].node)
								{
									if(this.refs[j].node[i].sel)
									{
										this.startNode=this.refs[j].node[i];
										sm.consumeEvent('proceedToDrawLine');
										break;
									}
								
								}
    						}
			
	case "drawLine":		this.ctxt.lineWidth = 1;
							this.ctxt.fillStyle = "#ffffff";
							this.ctxt.strokeStyle = "#000000";
							this.ctxt.beginPath();
							this.ctxt.moveTo(this.startNode.frame.x + nsize, this.startNode.frame.y + nsize);
							this.ctxt.lineTo(this.mousePosition.x,this.mousePosition.y);
							this.ctxt.closePath();
							this.ctxt.stroke(); 
							
							this.renderMap();
    						break;
    						
    case "addCon":   
    						for (var j in this.refs)
    						{
								for (var i in this.refs[j].node)
								{
									if(this.refs[j].node[i].sel)
									{
										this.cons.push( new Con(this.startNode,this.refs[j].node[i]));
										sm.consumeEvent('backToIdle');
									}
								}
    						}
    						
    						this.renderMap();
    						break;
    						
    						
    case "deleteObject":	var refkey=null;
    						var delflag=true;
    	
    						for(refkey in this.refs) 
    							if(this.refs[refkey].sel)   								
    								break;    							
    						
    						while(delflag)
    						{
    						 delflag=false;
    						 for (var j in this.cons)
    						 {
    							for (var i in this.refs[refkey].node)
    							   if( (this.refs[refkey].node[i] == this.cons[j].from) || (this.refs[refkey].node[i] == this.cons[j].to) )	
    								   delflag=true;
    							
    							if(delflag)
    							{
    								this.cons.splice(j,1);
    								break;
    							}
    						 }
    						}
    						
    						this.refs.splice(refkey,1);
   							this.renderMap();    
   							this.literaturelist();
    						sm.consumeEvent('backToIdle');
    						break; 
    						
    case "zoomState":		//alert("zoom value "+this.zoomdelta);
    						if( !((this.liveh <= 36) && (this.zoomdelta<0)) )
    						{
							  this.livew = this.livew*(1+this.zoomdelta/10);
							  this.liveh = this.liveh*(1+this.zoomdelta/10);	    
    						  for (var j in this.refs)
    							  this.refs[j].zoom(this.zoomdelta/10, this.mousePosition);
    						}
    						this.roundedRect(this.mousePosition.x - this.livew/2, this.mousePosition.y - this.liveh/2 , this.livew , this.liveh , 16, document.getElementById("paper").value );
   							this.renderMap();
    
    						sm.consumeEvent('backToIdle');
    						break; 
    						
    case "panState":		
    	                    if(this.refs[0])
    	                    {
    						  var deltax = 0;
							  var deltay = 0;
 							  if(!this.draghelper)
							  {
								deltax = this.mousePosition.x - this.refs[0].oldstart.x;
   							    deltay = this.mousePosition.y - this.refs[0].oldstart.y;
   							  }
   							  this.draghelper=false;
							
							  for (var j in this.refs)
    						  {
    							this.refs[j].frame.x += deltax;
								this.refs[j].frame.y += deltay;
								for (var i in this.refs[j].node)
								{
									this.refs[j].node[i].frame.x += deltax;
									this.refs[j].node[i].frame.y += deltay;
								}
    						  }
							
							  this.refs[0].oldstart=this.mousePosition;
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
		var objClicked = false;
		//check if an object got clicked
		for (var j in this.refs)
		{
			for (var i in this.refs[j].node)
			{
				if(this.refs[j].node[i].sel==true)
				{
					objClicked=true;
					sm.consumeEvent('clickOnNode');   	    
					break;
				}
			}
       
			//check Refs
			if(this.refs[j].sel==true && !objClicked)
			{
				objClicked=true;
				document.getElementById("paper").value;
				this.refs[j].drag=true;
				sm.consumeEvent('clickOnRef');
				this.draghelper=true;
				for (var j in this.cons)
					this.cons[j].paint(this);
				
				for (var j in this.refs)
					this.refs[j].paint(this);
			}
		}
	
		if(objClicked==false)
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
	for (var j in this.refs)
    {
		if(this.refs[j].frame.areacontains(this.mousePosition))
		{
		    if(this.refs[j].frame.contains(this.mousePosition))
		    {
				this.refs[j].sel=true;
				flag=true;
			}
		    else
		 	{
				this.refs[j].sel=false;			
			}
			
			for (var i in this.refs[j].node)
    		{
    		  if (this.refs[j].node[i].frame.contains(this.mousePosition))
    		  {  
    		    this.refs[j].node[i].sel=true;
    		    flag=true;
    		  }
    		  else
    		  {
    		    this.refs[j].node[i].sel=false;    		  
    		  }
			}		    
		}
		else
		{
			this.refs[j].sel=false;
			for (var i in this.refs[j].node)		
    			this.refs[j].node[i].sel=false;
		}
						
    }
    
    if(!flag)
    {
    	for (var j in this.cons)
    	{
    		if(this.cons[j].contains(this.mousePosition))
    			this.cons[j].sel=true;
    		else
    			this.cons[j].sel=false;
    	}
    }
	
	this.performState();
	this.stopEvent(e); 
};

//check if this paper is already in the mind map
MindMap.prototype.checkNames = function( papername )
{
	for (var i in this.refs)
	{			
		if(this.refs[i].title==papername)
			return true;
	}
	return false;
};


MindMap.prototype.key = function(e)
{
	if ( (e.keyCode == 46) || (e.keyCode == 8)  ) 
		sm.consumeEvent('deletePressed');	
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
	this.mousePosition = new Point(e.pageX - this.canvas.offsetLeft, e.pageY - this.canvas.offsetTop);
};

MindMap.prototype.attachRef = function()
{
	this.updateMouseAttachment();
	for (var j in this.cons)
		this.cons[j].paint(this);	
	for (var j in this.refs)
		this.refs[j].paint(this);
};


MindMap.prototype.roundedRect = function(x,y,w,h,r, text )
{
	this.ctxt.lineWidth = 1;
	this.ctxt.fillStyle = "#ffffff";
	this.ctxt.strokeStyle = "#000000";
	 
	this.ctxt.beginPath();
	this.ctxt.moveTo(x + r, y);
	this.ctxt.lineTo(x + w - r, y);
	this.ctxt.quadraticCurveTo(x + w, y, x + w, y + r);
	this.ctxt.lineTo(x + w, y + h - r);
	this.ctxt.quadraticCurveTo(x + w, y + h, x + w - r, y + h);
	this.ctxt.lineTo(x + r, y + h);
	this.ctxt.quadraticCurveTo(x, y + h, x, y + h - r);
	this.ctxt.lineTo(x, y + r);
	this.ctxt.quadraticCurveTo(x, y, x + r, y);
	this.ctxt.closePath();
	this.ctxt.fill();
	this.ctxt.stroke();
	
	if(text)
	{
 	  var textsize=w/15;
	  var temp = text.split(",")[0];
	  var etal="";
	  if(temp[1])
	  {
  		 etal = " et al.";
	  }
	  
	  var author = temp.split(".")[0] + "." + temp.split(".")[1] + etal;
	  var year = text.split(".  ")[1];
	  var title = text.split(".  ")[2];
	
	  this.ctxt.fillStyle = "blue";
	  this.ctxt.font = "italic "+textsize+"pt Arial";
      this.ctxt.lineWidth = 1;
	  this.ctxt.fillText( author , x + w/2 - this.ctxt.measureText(author).width/2, y+h/6);
	
	  this.ctxt.fillStyle = "blue";
	  this.ctxt.font = "italic "+textsize+"pt Arial";
      this.ctxt.lineWidth = 1;
	  this.ctxt.fillText( year , x + w/2 - this.ctxt.measureText(year).width/2, y+h/3);

	  title = "\""+title+"\"";
	  textsize=w/20;
      this.ctxt.fillStyle = 'green';
      this.ctxt.font = "italic "+textsize+"pt Calibri";
      this.ctxt.lineWidth = 1;
    	
      var wc = title.split(" ");
      var line = "";
      var lineheight=0;
      //console.log(wc.length);
        
      for(var i = 0; i < wc.length; i++) 
      {
        var test = line + wc[i] + " ";            
        if(this.ctxt.measureText(test).width > w * 0.98 ) 
        { 
          if( lineheight < h/2 )
          {
            this.ctxt.fillText(line, x + w/2 - this.ctxt.measureText(line).width/2, y + h/2 + lineheight);
            line = wc[i] + " ";
            lineheight += textsize+5;            
          }
        }
        else
        {   
       	  line = test;
        }
      }
      if( lineheight < h/2 )
      {
    	  this.ctxt.fillText(line, x + w/2 - this.ctxt.measureText(line).width/2, y + h/2 + lineheight);
      }
	}
	else
	{
		  this.ctxt.fillStyle = "orange";
		  var textsize=w/20;
		  this.ctxt.font = "italic "+textsize+"pt Arial";
	      this.ctxt.lineWidth = 1;
	      var string = "Please select a paper ...";
		  this.ctxt.fillText( string , x + w/2 - this.ctxt.measureText(string).width/2, y+h/6);
	}
};

MindMap.prototype.renderMap = function()
{
	for (var j in this.cons)
		this.cons[j].paint(this);
	for (var j in this.refs)
		this.refs[j].paint(this);
};

MindMap.prototype.literaturelist = function()
{
	document.getElementById("thelist").value="";
	var string="";
	var j;
	for (var i in this.refs)
	{   
		if( (j=this.getPaperIndex(this.refs[i].title.split(".  ")[2]) ) >=0 )
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
	return { "Ref": {
		           "0":{
		        	   "title":"firsttitle",
		        	   "position":"theposition"
		               },
		           "1":{
			           "title":"sndtitle",
			           "position":"the2position"
			           }	            
				}, 
			 "Con": {"name": "somename"},
			 "metadata": {"zoom": "15"}
	};
};