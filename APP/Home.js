/**
 * Sample React Native App
 * https://github.com/facebook/react-native
 *
 * @format
 * @flow
 */

import React,{Component} from 'react';
import {
  SafeAreaView,
  StyleSheet,
  ScrollView,
  View,
  TextInput,
  StatusBar,
  TouchableOpacity,
  Image,
  AsyncStorage
 
} from 'react-native';
import {Icon, Container, Header, Content, Footer, FooterTab, Button, Text,Item,Input,Form,Badge ,Avatar} from 'native-base';
import Toast, {DURATION} from 'react-native-easy-toast'
import PickerModal from 'react-native-picker-modal-view';
import { NavigationEvents,NavigationActions } from 'react-navigation';
const list22 = [
	{Id: 1, Name: 'CHL', Value: 'Test1 Value'},
	{Id: 2, Name: 'Test2 Name', Value: 'Test2 Value'},
	{Id: 3, Name: 'Test3 Name', Value: 'Test3 Value'},
	{Id: 4, Name: 'Test4 Name', Value: 'Test4 Value'}
]

  export default class Home extends Component {
   
    constructor(props) {
      super(props);
      this.state = {
        selectedItem: {},
        carelistdata:[],
        selectedcarename:'Select Care Unit',
        notificationcount:''
      };
    }
  
    selected(selected) {
      this.setState({
        selectedItem: selected
      })
    }
    searchPatient() {
   
      if(this.state.selectedcare)
      {
      this.props.navigation.navigate('Patientlist',{care_unit_id:this.state.selectedcare});
      }
      else{
        this.refs.toast.show(<View style={{ justifyContent:"center",alignItems:"center"}}>
        <Text style={{color:'white'}}>
        Please select Care Unit.
      
        </Text>
        </View>); 
        }
       // alert(2323)
        }
        addnewPatient()
        {
     
           this.props.navigation.navigate('Newentry'); 
        }
    searchpatientBTN()
    {

   if(this.state.EnterId )
    {

      AsyncStorage.getItem('userdata', (err, result) => {
      

        const item = JSON.parse(result);
        const key = item.login_session_key;

        let formdata = new FormData();
       formdata.append("login_session_key",key)

       formdata.append("patient_id",this.state.EnterId)
       console.log(formdata)

       fetch('http://idcaresteward.com/api/v1/user/patient_list_existing',{
        method: 'post',
         headers: {
        'Content-Type': 'multipart/form-data',
         },
          body: formdata
         })
      .then((response) => response.json())
      .then((responseJson) => {
   console.log(responseJson)

       if(responseJson.status==1)
        {
       
         // this.props.navigation.navigate('EditPentry',{patient_id:this.state.EnterId,care_unit_id:this.state.selectedcare}); 
          this.props.navigation.navigate('DignosisList', {
            patient_id: this.state.EnterId,care_unit_id:responseJson.response[0].care_unit_id
         
          });
      
       }
      else{
        this.setState({
          animating: false
        })
        this.refs.toast.show(<View style={{ justifyContent:"center",alignItems:"center"}}>
        <Text style={{color:'white'}}>
       {responseJson.message}
      
        </Text>
        </View>); 
      }
   

    

     
      })
     .catch(err => {
   //  console.log(err)
     })  
    })


}
else{
  this.refs.toast.show(<View style={{ justifyContent:"center",alignItems:"center"}}>
  <Text style={{color:'white'}}>
  Please Enter Patient Unique ID.

  </Text>
  </View>); 
} 
}

    componentWillMount(){

      
      AsyncStorage.getItem('userdata', (err, result) => {
        const item = JSON.parse(result);
        const key = item.login_session_key;
       // alert(JSON.stringify(item.username))
        this.setState({
          login_role: item.login_role
        })
        this.setState({
          username: item.username
        })

   let formdata = new FormData();
       formdata.append("login_session_key",key)
       console.log(formdata)
       fetch('http://idcaresteward.com/api/v1/user/careUnit',{
        method: 'post',
         headers: {
        'Content-Type': 'multipart/form-data',
         },
          body: formdata
         })
      .then((response) => response.json())
      .then((responseJson) => {
     console.log(responseJson)
       if(responseJson.status==1 )
       {
       this.items = responseJson.response.map((item, key) =>
        this.setState({
        carelistdata: [...this.state.carelistdata, {'Id':item.id, 'Name':item.name,'Value':item.id}]
         })
         
         );
       }
        else if(responseJson.status==2)
        {
        this.logout();
        }
     
      })
     .catch(err => {
   //  console.log(err)
     })  

    //  second

    let formdata2 = new FormData();
    if(item.login_role=='Md Steward')
    {
      formdata2.append("md_steward_id",item.user_id)
    }
   
    formdata2.append("login_session_key",key)

     //end//



 fetch('http://idcaresteward.com/api/v1/user/notification_list',{
  method: 'post',
   headers: {
  'Content-Type': 'multipart/form-data',
   },
    body: formdata2
   })
.then((response) => response.json())
.then((responseJson) => {


 if(responseJson.status==1 )
 {
 
let datalength=responseJson.response.length;
console.log(responseJson.response.length)
  this.setState({
  notificationcount:datalength
   })

}

   
//    );

})
.catch(err => {




    })
  })

    }

    loaddata(){

      AsyncStorage.getItem('userdata', (err, result) => {
        const item = JSON.parse(result);
        const key = item.login_session_key;
       // alert(JSON.stringify(item.username))
        this.setState({
          login_role: item.login_role
        })
        this.setState({
          username: item.username
        })

        console.log(232323)
    //  second

    let formdata2 = new FormData();
    if(item.login_role=='Md Steward')
    {
      formdata2.append("md_steward_id",item.user_id)
    }
   
    formdata2.append("login_session_key",key)

     //end//

     console.log(formdata2)

 fetch('http://idcaresteward.com/api/v1/user/notification_list',{
  method: 'post',
   headers: {
  'Content-Type': 'multipart/form-data',
   },
    body: formdata2
   })
.then((response) => response.json())
.then((responseJson) => {

  console.log(responseJson)
 if(responseJson.status==1 )
 {
 
let datalength=responseJson.response.length;
console.log(responseJson.response.length)
  this.setState({
  notificationcount:datalength
   })

}
else{
  this.setState({
    notificationcount:''
     })
}

   
//    );

})
.catch(err => {




    })
  })

    }

    selectedcare(selected) {
     
      //alert(JSON.stringify(selected))
    if(selected.Name)
    {
        this.setState({
          selectedcare: selected.Value
        })
        this.setState({
          selectedcarename: selected.Name
        })
      }
      else{
      //  alert('dfd')
        this.setState({
          selectedcarename: 'Select Care Unit'
        })
      }
      }
      gonotification()
      {
      
        this.props.navigation.navigate('Notificationlist');  
      }

      // async logout() {
      //   const error = {}
      //   try {
      //     await AsyncStorage.removeItem('userdata');
      //     await AsyncStorage.removeItem('Season');
      //     this.props.navigation.reset([NavigationActions.navigate({ routeName: 'Roleselection' })]);
      //     Alert.alert('WITEAMS', "Logout Successfully", [
      //       {
      //         text: 'OK'
      //       }
      //     ], { cancelable: true })
      //   } catch (error) {
      //    // alert('AsyncStorage error: ' + error.message);
      //   }
      // }

      logout(){
        AsyncStorage.getItem('userdata', (err, result) => {
          const item = JSON.parse(result);
          const key = item.login_session_key;
  
          let formdata = new FormData();
         formdata.append("login_session_key",key)
         formdata.append("user_id",item.user_id)
         
      
         fetch('http://idcaresteward.com/api/v1/user/logout',{
          method: 'post',
           headers: {
          'Content-Type': 'multipart/form-data',
           },
            body: formdata
           })
        .then((response) => response.json())
        .then((responseJson) => {
       
        console.log(responseJson);
         AsyncStorage.removeItem('userdata');
       // await AsyncStorage.removeItem('Season');
        //this.props.navigation.navigate('Roleselection'); 
        this.props.navigation.reset([NavigationActions.navigate({ routeName: 'Roleselection' })]);
       
        })
       .catch(err => {
     //  console.log(err)
       })  
      })
      }
      
    render() {
      
  return (


    <SafeAreaView>
       <StatusBar barStyle='dark-content' backgroundColor='transparent' ></StatusBar>  
    {/* <Header /> */}
    {/* <Toast ref="toast"  position='center'/> */}
    <Toast ref="toast"  />
    {/* <View style={{width:'97%',backgroundColor:'green', margin:1, flexDirection:'row',}} >

       <View style={{ width:'55%',backgroundColor:'red',alignItems:'flex-end'}}>
       <Text style={{fontWeight:'bold',color:'#828282',fontSize:15,paddingTop:5}}>Home</Text>
       </View>
       <TouchableOpacity style={{  backgroundColor:'green',padding:5,  width:'45%',alignItems:'flex-end'}} onPress={this.logout.bind(this)}> 
     
       <Image style={{width:18,height:24}}  source={require('./image/logout.png')} ></Image>
       </TouchableOpacity>

       </View> 
  */}
     <View style={{ backgroundColor:'#B22B57'}}>

    <View style={{ flexDirection:'column'}}>
<ScrollView style={{ paddingRight:20, paddingLeft:20,height:'82%', borderBottomLeftRadius:20,borderBottomRightRadius:20,backgroundColor:'white'}}>
{/* <NavigationEvents onDidFocus={() => this.loaddata()} /> */}
<NavigationEvents
     // onWillFocus={() => this.loaddata()}
      onDidFocus={() => this.loaddata()}
      onWillBlur={payload => console.log('will blur', payload)}
      onDidBlur={payload => console.log('did blur', payload)}
    />
      
     <View style={{ width:'100%', margin:1, flexDirection:'row',height:40}} >

       <View style={{ width:'56%',alignItems:'flex-end',paddingTop:7}}>
       <Text style={{fontWeight:'bold',color:'#828282',fontSize:15,paddingTop:10}}>Home</Text>
       </View>
      
       { this.state.login_role=="Md Steward" ?

         
    this.state.notificationcount ?
       <View style={{marginLeft:50}}>
        
   
      {/* <TouchableOpacity style={{ paddingTop:10,flexDirection:'row'}} onPress={this.gonotification.bind(this)}>
    
       <Icon  name='bell' type="FontAwesome" style={{fontSize: 18, color: '#B22B57'}} />
       <View style={{padding:5, backgroundColor:'#B22B57',height:20,width:20,borderRadius:20}}>
       <Text style={{color:'white',fontSize:8,textAlign:'center'}}>{this.state.notificationcount}</Text>
      </View>
    </TouchableOpacity> */}
         <View style={{width:50, flexDirection:'row', paddingTop:10, width:'20%',marginTop:11,}} > 
      <TouchableOpacity style={{height:20}} onPress={this.gonotification.bind(this)}> 
     <Image style={{ tintColor:'#B22B57',width:17,height:18,paddingTop:10}}  source={require('./image/notification33.png')} ></Image>
     </TouchableOpacity>
     <TouchableOpacity onPress={this.gonotification.bind(this)} style={{marginLeft:12, position: 'absolute', marginBottom:10, padding:4, backgroundColor:'#B22B57',height:20,width:20,borderRadius:20}}>
       <Text style={{color:'white',fontSize:9,textAlign:'center'}}>{this.state.notificationcount}</Text>
      </TouchableOpacity>
    
      </View>
        {/* <TouchableOpacity style={{paddingTop:10, flexDirection:'row', marginLeft:50, padding:5,paddingLeft:10,  width:'10%'}} onPress={this.gonotification.bind(this)}> 
     
        
        <Icon  name='bell' type="FontAwesome" style={{fontSize: 18, color: '#B22B57'}} /> 
       
        </TouchableOpacity> */}
 
        </View>
        :
          <TouchableOpacity style={{paddingTop:20, flexDirection:'row', marginLeft:50, padding:5,paddingLeft:10,  width:'10%'}} onPress={this.gonotification.bind(this)}> 
     
     <Image style={{ tintColor:'#B22B57',width:16,height:18,paddingTop:10}}  source={require('./image/notification33.png')} ></Image>
        {/* <Icon  name='bell' type="FontAwesome" style={{fontSize: 18, color: '#B22B57'}} />  */}
       
        </TouchableOpacity> 
       
        :
        <View style={{marginLeft:32, padding:5,paddingLeft:10,  width:'15%',alignItems:'flex-end'}} onPress={this.gonotification.bind(this)}> 
     
    
       
        </View>
   
       }
       <TouchableOpacity style={{ paddingTop:20,  padding:5,marginLeft:20,  width:'8%',alignItems:'flex-end'}} onPress={this.logout.bind(this)}> 
     
       <Image style={{width:14,height:19}}  source={require('./image/logout.png')} ></Image>
       </TouchableOpacity>

       </View> 
  
       <View>



</View>

       <View style={{alignItems:'center',marginTop:7}} >
      <Text style={{fontSize:13,padding:5,paddingBottom:10, color:'#828282'}}>Logged in as {this.state.username}</Text>
         <Image style={{width:125,height:125,marginBottom:2}}  source={require('./image/search_.png')} ></Image>
       
        

<View style={{width:'100%',padding:15,height:60}}>
<Text style={{fontSize:14,color:'#828282',paddingBottom:10,paddingLeft:10}}>Select Care Unit First To List Patients</Text>
        <PickerModal
         renderSelectView={(disabled, selected, showModal) =>
        // <Item >
      //  <TouchableOpacity onPress={showModal}  
      //    style={{ flexDirection:'row',alignItems:'center', width:'89%', backgroundColor:'#F7F7F7',height:37,borderRadius:7}}>
      //  <View style={{width:'95%'}}>
      //   <Text style={{paddingLeft:10,color:'#747474',fontSize:14}} >{this.state.selectedcarename}</Text>
      //   {/* <Icon style={{ color:'#858585',height:50,paddingTop:10, backgroundColor:'#F7F7F7'}} active name='caretdown' />  */}
      //   </View>
      //   <View style={{ flexDirection:'row', height:37,width:'17%',backgroundColor:'#F7F7F7',justifyContent:'center',alignItems:'center',borderRadius:8}}>
      //   <Image style={{ tintColor:"#878787", width:12,height:10,  marginRight:10,justifyContent:'flex-end',marginLeft:30}}    onTouchStart={showModal}  source={require('./image/drop-down-arrow.png')} ></Image>
      //   </View>
      //  </TouchableOpacity>

<View style={{
  flexDirection:'row',alignItems:'center', width:'100%', backgroundColor:'#F7F7F7',height:37,borderRadius:7}}>
  <TouchableOpacity onPress={showModal} style={{ width:'60%',}}   >
  <View style={{ flexDirection:'row', width:'95%'}}>
   <Text style={{paddingLeft:10,color:'#747474',fontSize:14}} >{this.state.selectedcarename}</Text>
   {/* <Icon style={{ color:'#858585',height:50,paddingTop:10, backgroundColor:'#F7F7F7'}} active name='caretdown' />  */}
  
   </View>
   </TouchableOpacity>
<TouchableOpacity  onPress={showModal} style={{  borderTopRightRadius:7,borderBottomRightRadius:7, flexDirection:'row', height:37,width:'40%',backgroundColor:'#F7F7F7',justifyContent:'flex-end',alignItems:'center'}}> 
  
  
    <Image onPress={showModal}  style={{ tintColor:"#878787", width:12,height:10,  marginRight:10,justifyContent:'flex-end',marginLeft:30}}    onTouchStart={showModal}  source={require('./image/drop-down-arrow.png')} ></Image> 
 </TouchableOpacity> 
 </View>
      
        // </Item>
        }
		  	onSelected={(selected) => this.selectedcare(selected)}
		  	onRequestClosed={()=>this.setState({
          selectedcarename: 'Care Unit Name'
        })}
		  	onBackRequest={()=>this.setState({
          selectedcarename: 'Care Unit Name'
        })}
		  	items={this.state.carelistdata}
		  	sortingLanguage={'tr'}
		  	showToTopButton={true}
		  	defaultSelected={this.state.selectedItem}
		  	autoCorrect={false}
		  	autoGenerateAlphabet={true}
			  searchText={'Search...'} 
			  forceSelect={false}
         autoSort={true}
         backgroundColor='red'
	    	/>

        

       </View>

   <TouchableOpacity  onPress={this.searchPatient.bind(this)} style={{paddingTop:10, width:'30%', marginTop:20,height:40,marginLeft:'60%', justifyContent:'flex-end',flexDirection:'row'}}>
    <Text style={{ fontSize:12, color:"#B22B57",textDecorationLine:'underline'}}>List Patients</Text>
    </TouchableOpacity>
    <View style={{width:'90%',justifyContent:'flex-start',flexDirection:'row',borderColor:'#B22B57',borderTopWidth:1,paddingBottom:15}}>
    </View>
   <View style={{width:'90%',justifyContent:'flex-start',flexDirection:'row'}}>
   {/* color:"#828282" */}
   <Text style={{fontSize:15, color:"#828282",paddingLeft:7,}}>Search Existing Patient</Text>
   </View>

   <View  style={{ margin:15,paddingBottom:10, flexDirection:'row',alignItems:'center', width:'89%', backgroundColor:'#F7F7F7',height:37,borderRadius:7}}>
         
          
            <Input 

            style={{paddingLeft:7, backgroundColor:'#F7F7F7',height:36,borderTopLeftRadius:7,borderBottomLeftRadius:7,color:'#747474'}}
        onChangeText={EnterId => this.setState({EnterId})}
      //  onSubmitEditing={() => this.password.focus()}
         placeholder='Enter Patient Unique ID'
         placeholderTextColor='#747474'
         fontSize={15}
         
         />
         <View style={{backgroundColor:'#F7F7F7',height:37, borderTopRightRadius:7,borderBottomRightRadius:7}}>
          <Icon  style={{ fontSize:16, color:'#878787',height:50,paddingTop:15,paddingRight:5}} active name='search' />
          </View>
    </View>


             
             <TouchableOpacity   onPress={this.searchpatientBTN.bind(this)}   style={{  borderRadius:7,  backgroundColor:'#B22B57',width:'90%',height:50,alignItems:'center',justifyContent:'center'}} >
                
                    <Text style={{color:'white',fontSize:18}}> Search </Text>
                 
                </TouchableOpacity> 

              
             
</View>
</ScrollView>
           <View style={{height:'18%'}}>
           {this.state.login_role=="Data Operator" ? 
           <View style={{height:'100%', flexDirection:'row',justifyContent:'space-around',alignItems:'center'}}>
            <View style={{  justifyContent:"center"}}>
             <Text style={{color:'white'}}>Register </Text>
             <Text style={{color:'white'}}>A New Patient </Text>
           </View>
          
             <TouchableOpacity style={{justifyContent:'center',alignContent:'center',alignItems:"center", width:45,height:45, backgroundColor:'white',padding:5,borderRadius:9}} onPress={this.addnewPatient.bind(this)}> 
              {/* <Icon style={{color:'white',fontWeight:'bold'}} name='add' /> */}
              <Image style={{width:15,height:15,tintColor:'#3B3B3B'}}  source={require('./image/plus_sign.png')} ></Image> 
             </TouchableOpacity>

            
          
            </View>
            :

            <View style={{height:'100%', flexDirection:'row',justifyContent:'space-around',alignItems:'center'}}>
            <View style={{  justifyContent:"center"}}>
             <Text style={{color:'gray'}}>Register </Text>
             <Text style={{color:'gray'}}>A New Patient </Text>
           </View>
          
            

            
             <TouchableOpacity disabled='true' style={{justifyContent:'center',alignContent:'center',alignItems:"center", width:45,height:45, backgroundColor:'gray',padding:5,borderRadius:9}}> 
             {/* <Icon style={{color:'white',fontWeight:'bold'}} name='add' /> */}
             <Image disabled='true' style={{width:15,height:15,tintColor:'#3B3B3B'}}  source={require('./image/plus_sign.png')} ></Image> 
            </TouchableOpacity>
            </View>
         }

           </View>
       
    
   </View>

   
   </View>

  </SafeAreaView>

  );
}
};


const styles = StyleSheet.create({
  textInput: {
    minHeight: 28,
    fontSize: 12,
  //  fontFamily: AppFonts.HNMedium,
    backgroundColor: 'transparent',
    color: '#fff',
    width: "90%",
    letterSpacing: 0.9,
    borderRadius:25,
    borderColor:'transparent',
    },
  
});

