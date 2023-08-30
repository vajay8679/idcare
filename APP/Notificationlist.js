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
  AsyncStorage,
  ActivityIndicator,
  FlatList

 
} from 'react-native';
import Toast, {DURATION} from 'react-native-easy-toast'
import {Icon, Container, Header, Content, Footer, FooterTab, Button, Text,Item,Input,Form,ListItem, CheckBox, Body } from 'native-base';

import PickerModal from 'react-native-picker-modal-view';
const list22 = [
	{Id: 1, Name: 'CHL', Value: 'Test1 Value'},
	{Id: 2, Name: 'Test2 Name', Value: 'Test2 Value'},
	{Id: 3, Name: 'Test3 Name', Value: 'Test3 Value'},
	{Id: 4, Name: 'Test4 Name', Value: 'Test4 Value'}
]


const CustomHeader = ({ navigation }) => (

    <View style={{ flexDirection: 'row' ,justifyContent:'center',backgroundColor:'blue'}}>
     <TouchableOpacity onPress={()=> navigation.goBack(null)} style={{flex:1,justifyContent:"flex-start"}} transparent>
     <Image
      source={require('./image/BackLight.png')}
    /> 
   
            </TouchableOpacity>
            
      <View style={{  justifyContent: 'center', paddingRight: 15 }}>
        {/* <Text>Conference</Text> */}
      </View>

    </View>

);
 
  export default class Notificationlist extends Component {
    // static navigationOptions = ({ navigation }) => {
    //   return {
    //     header: <CustomHeader this navigation={navigation} />
    //   };
    // };
    constructor(props) {
      super(props);
      this.state = {

        notificationlistdata:[],
       
      };
    }
  


    componentWillMount()
    {
      this.setState({
        animating: true
      })
     
      AsyncStorage.getItem('userdata', (err, result) => {


        const item = JSON.parse(result);
        const key = item.login_session_key;
  
        
        let formdata = new FormData();
        if(item.login_role=='Md Steward')
        {
        formdata.append("md_steward_id",item.user_id)
        }
       
         formdata.append("login_session_key",key)
 
         //end//
    


     fetch('http://idcaresteward.com/api/v1/user/notification_list',{
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
     
   
    //  this.items = responseJson.response.map((item, key) =>
      this.setState({
      notificationlistdata: responseJson.response
       })
       this.setState({
        animating: false
      })
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
       
    //    );
   
    })
   .catch(err => {
   console.log(err)
   this.setState({
    animating: false
  })
  this.refs.toast.show(<View style={{ justifyContent:"center",alignItems:"center"}}>
  <Text style={{color:'white'}}>
 {responseJson.message}

  </Text>
  </View>); 
   })  

    
       //end//
      }) 
    }

 
   
    goback() {
      // this.props.navigation.navigate('Home');
      // alert(2323)
      this.props.navigation.goBack();
     }
     gohome(){
      this.props.navigation.goBack();
     }
     EditPentry(id,care_unit_id) {
     
      // this.props.navigation.navigate('EditPentry', {
      //   patient_id: id,
     
      // });
      this.props.navigation.navigate('DignosisList', {
        patient_id: id,care_unit_id:care_unit_id
     
      });
     // alert(2323)
    }
    
    render() {
      const { navigation } = this.props;
      let data=''
     // console.log(this.state.notificationlistdata.length)
         if(this.state.notificationlistdata )
         {
      
          data=  <FlatList
              data={this.state.notificationlistdata}
              renderItem={({ item }) => <TouchableOpacity onPress={this.EditPentry.bind(this,item.patient_id,item.care_unit_id)} style={{ flexDirection:'row', width:'100%',paddingLeft:15,justifyContent:'flex-start',height:60,}}>
            <View style={{ width:'70%'}}>
            <Text >{item.message} </Text>
            <Text style={{fontSize:12}}>{item.sent_time}</Text>
            </View>
            <View style={{paddingRight:20, alignContent:'flex-end',alignItems:'flex-end', flex:4}}>
              <Text style={{color:'#B22B57',textDecorationLine:'underline'}}>Review</Text>
            </View>
            </TouchableOpacity>}
            //  keyExtractor={item => item.id}
            />
         }
         else{
          data= <View>
          <Text style={{textAlign:'center'}}>No Record Found.</Text>
          </View>
      
         }
         
           
          
            
        return (
        
          <SafeAreaView style={{backgroundColor:'#B22B57',flex:1}} >
           
             <StatusBar barStyle='light-content' backgroundColor='#B22B57' ></StatusBar>  
             
              <Content style={{backgroundColor:'white',}}>
          {/* <Header /> */}
          <Toast ref="toast"  position='center' fadeOutDuration={1000}/>
          <View style={{ paddingTop:30, flexDirection:'row',justifyContent:'space-around',backgroundColor:"#B22B57",height:60,borderBottomLeftRadius:20,borderBottomRightRadius:20}} >
          <TouchableOpacity  onPress={this.goback.bind(this)}  style={{ height:40, width:'30%',alignItems:'flex-start'}}>
         
          <Image style={{width:15,height:15,tintColor:'white',marginLeft:10}}  source={require('./image/back.png')} ></Image>
             </TouchableOpacity>
             <View style={{ width:'30%',alignItems:'center'}}>
             <Text style={{fontWeight:'bold',color:'white',fontSize:15}}>Notifications</Text>
             </View>
             <TouchableOpacity   onPress={this.gohome.bind(this)}  style={{ width:'30%',alignItems:'flex-end'}}>
            
             <Image style={{marginRight:15, width:15,height:15,tintColor:'white'}}  source={require('./image/home.png')} ></Image>
           </TouchableOpacity>
          
             </View> 
           
          
       
           <View style={{ flex: 1,marginTop:10}}>
            {this.state.animating==true &&
                  <ActivityIndicator
                     animating = {this.state.animating}
                     color = '#B22B57'
                     size = "large"
                     style = {styles.activityIndicator}/>
           } 
      
         
        {data} 
       
            </View>
      
      
           {/* <View style={{ width:'100%', flexDirection:'row',justifyContent:'center',height:40,alignItems:'center'}}>
             <View style={{ width:'30%'}}>
              <Text style={{textAlign:'left'}} >45969696</Text>
            </View>
             <View style={{width:'30%'}}>
             <Text style={{textAlign:'center'}} >David Menkt</Text>
             </View>
             <TouchableOpacity style={{width:'30%'}}>
             <Text style={{textAlign:'center',color:'#484B79',textDecorationLine:'underline'}}>View</Text>
             </TouchableOpacity>
           </View>
      
           <View style={{ width:'100%', flexDirection:'row',justifyContent:'center',height:40,alignItems:'center'}}>
             <View style={{ width:'30%'}}>
              <Text style={{textAlign:'left'}} >234</Text>
            </View>
             <View style={{width:'30%'}}>
             <Text style={{textAlign:'center'}} >David Menkt</Text>
             </View>
             <TouchableOpacity style={{width:'30%'}}>
             <Text style={{textAlign:'center',color:'#484B79',textDecorationLine:'underline'}}>View</Text>
             </TouchableOpacity>
           </View>
      
           <View style={{ width:'100%', flexDirection:'row',justifyContent:'center',height:40,alignItems:'center'}}>
             <View style={{ width:'30%'}}>
              <Text style={{textAlign:'left'}} >567</Text>
            </View>
             <View style={{width:'30%'}}>
             <Text style={{textAlign:'center'}} >David Menkt</Text>
             </View>
             <TouchableOpacity style={{width:'30%'}}>
             <Text style={{textAlign:'center',color:'#484B79',textDecorationLine:'underline'}}>View</Text>
             </TouchableOpacity>
           </View>
           <View style={{ width:'100%', flexDirection:'row',justifyContent:'center',height:40,alignItems:'center'}}>
             <View style={{ width:'30%'}}>
              <Text style={{textAlign:'left'}} >567</Text>
            </View>
             <View style={{width:'30%'}}>
             <Text style={{textAlign:'center'}} >David Menkt </Text>
             </View>
             <TouchableOpacity style={{width:'30%'}}>
             <Text style={{textAlign:'center',color:'#484B79',textDecorationLine:'underline'}}>View</Text>
             </TouchableOpacity>
           </View> */}
         
         </Content>
         </SafeAreaView>
      
      
        );
      }
      };
      
      
      const styles = StyleSheet.create({
      
        activityIndicator: {
         // flex: 1,
       //  position: 'absolute',
         left: 0,
         right: 0,
         top: 0,
         bottom: 0,
         alignItems: 'center',
         justifyContent: 'center',
         //backgroundColor:'#F5FCFF',
       //  backgroundColor: 'rgba(40, 40, 40, 0.5)',
         zIndex:9999
        }
       
      });
      
      
      